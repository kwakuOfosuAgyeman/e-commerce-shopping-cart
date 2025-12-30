<?php

namespace App\Jobs;

use App\Mail\LowStockAlert;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendLowStockNotification implements ShouldQueue
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
    public int $timeout = 60;

    /**
     * Calculate the number of seconds to wait before retrying the job.
     * Uses exponential backoff: 10s, 30s, 60s, 120s, 300s
     */
    public function backoff(): array
    {
        return [10, 30, 60, 120, 300];
    }

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Product $product
    ) {}

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): \DateTime
    {
        return now()->addHours(4);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Refresh product to get current stock
        $this->product->refresh();

        // Guard: Only send if product is still low on stock
        $threshold = $this->product->low_stock_threshold ?? 10;
        if ($this->product->stock > $threshold) {
            Log::info('Skipping low stock notification - stock has been replenished', [
                'product_id' => $this->product->id,
                'product_name' => $this->product->name,
                'current_stock' => $this->product->stock,
                'threshold' => $threshold,
            ]);
            return;
        }

        $adminUsers = User::where('is_admin', true)->get();

        if ($adminUsers->isEmpty()) {
            Log::warning('No admin users found to send low stock notification', [
                'product_id' => $this->product->id,
                'product_name' => $this->product->name,
            ]);
            return;
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($adminUsers as $admin) {
            try {
                Mail::to($admin->email)->send(new LowStockAlert($this->product));
                $sentCount++;
            } catch (Throwable $e) {
                $failedCount++;
                Log::warning('Failed to send low stock alert to admin', [
                    'admin_id' => $admin->id,
                    'admin_email' => $admin->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // If all emails failed, throw exception to trigger retry
        if ($sentCount === 0 && $failedCount > 0) {
            throw new \Exception("Failed to send low stock notification to any admin");
        }

        Log::info('Low stock notification sent', [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'current_stock' => $this->product->stock,
            'threshold' => $threshold,
            'sent_count' => $sentCount,
            'failed_count' => $failedCount,
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(?Throwable $exception): void
    {
        Log::error('Failed to send low stock notification after all retries', [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'error' => $exception?->getMessage(),
            'attempts' => $this->attempts(),
        ]);
    }
}
