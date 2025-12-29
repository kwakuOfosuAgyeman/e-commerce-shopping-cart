<?php

namespace App\Jobs;

use App\Mail\DailySalesReport;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReport implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 60;

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

        foreach ($adminUsers as $admin) {
            Mail::to($admin->email)->send(new DailySalesReport($reportData, $this->reportDate));
        }

        Log::info('Daily sales report sent', [
            'date' => $this->reportDate->toDateString(),
            'total_orders' => $reportData['total_orders'],
            'total_revenue' => $reportData['total_revenue'],
            'admin_count' => $adminUsers->count(),
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
    public function failed(\Throwable $exception): void
    {
        Log::error('Failed to send daily sales report', [
            'date' => $this->reportDate->toDateString(),
            'error' => $exception->getMessage(),
        ]);
    }
}
