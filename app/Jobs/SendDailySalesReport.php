<?php

namespace App\Jobs;

use App\Mail\DailySalesReport;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendDailySalesReport implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 5;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     */
    public int $maxExceptions = 3;

    /**
     * Indicate if the job should be marked as failed on timeout.
     */
    public bool $failOnTimeout = true;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 120;

    /**
     * Calculate the number of seconds to wait before retrying the job.
     * Uses exponential backoff: 30s, 60s, 120s, 300s, 600s
     */
    public function backoff(): array
    {
        return [30, 60, 120, 300, 600];
    }

    /**
     * The date for the report.
     */
    public Carbon $reportDate;

    /**
     * Create a new job instance.
     */
    public function __construct(?Carbon $reportDate = null)
    {
        $this->reportDate = $reportDate ?? Carbon::today();
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): \DateTime
    {
        return now()->addHours(6);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $adminUsers = User::where('is_admin', true)->get();

        if ($adminUsers->isEmpty()) {
            Log::warning('No admin users found to send daily sales report');
            return;
        }

        $reportData = $this->generateReportData();

        $sentCount = 0;
        $failedCount = 0;

        foreach ($adminUsers as $admin) {
            try {
                Mail::to($admin->email)->send(new DailySalesReport($reportData, $this->reportDate));
                $sentCount++;
            } catch (Throwable $e) {
                $failedCount++;
                Log::warning('Failed to send daily sales report to admin', [
                    'admin_id' => $admin->id,
                    'admin_email' => $admin->email,
                    'date' => $this->reportDate->toDateString(),
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // If all emails failed, throw exception to trigger retry
        if ($sentCount === 0 && $failedCount > 0) {
            throw new \Exception("Failed to send daily sales report to any admin");
        }

        Log::info('Daily sales report sent', [
            'date' => $this->reportDate->toDateString(),
            'total_orders' => $reportData['total_orders'],
            'total_revenue' => $reportData['total_revenue'],
            'sent_count' => $sentCount,
            'failed_count' => $failedCount,
        ]);
    }

    /**
     * Generate the report data for the day.
     */
    protected function generateReportData(): array
    {
        $startOfDay = $this->reportDate->copy()->startOfDay();
        $endOfDay = $this->reportDate->copy()->endOfDay();

        // Get orders for the day
        $orders = Order::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->with(['items.product', 'user'])
            ->get();

        // Get product sales summary
        $productSales = OrderItem::whereHas('order', function ($query) use ($startOfDay, $endOfDay) {
                $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
            })
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(price * quantity) as total_revenue')
            )
            ->with('product:id,name,sku,stock')
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->get();

        // Calculate totals
        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $totalItemsSold = $orders->sum(fn($order) => $order->items->sum('quantity'));

        // Get order status breakdown
        $statusBreakdown = $orders->groupBy('status')
            ->map(fn($group) => $group->count())
            ->toArray();

        return [
            'date' => $this->reportDate->toDateString(),
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'total_items_sold' => $totalItemsSold,
            'average_order_value' => $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0,
            'product_sales' => $productSales,
            'status_breakdown' => $statusBreakdown,
            'orders' => $orders,
        ];
    }

    /**
     * Handle a job failure.
     */
    public function failed(?Throwable $exception): void
    {
        Log::error('Failed to send daily sales report after all retries', [
            'date' => $this->reportDate->toDateString(),
            'error' => $exception?->getMessage(),
            'attempts' => $this->attempts(),
        ]);
    }
}
