<?php

namespace App\Jobs;

use App\Mail\LowStockAlert;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendLowStockNotification implements ShouldQueue
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
     * Create a new job instance.
     */
    public function __construct(
        public Product $product
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $adminUsers = User::where('is_admin', true)->get();

        if ($adminUsers->isEmpty()) {
            Log::warning('No admin users found to send low stock notification', [
                'product_id' => $this->product->id,
                'product_name' => $this->product->name,
            ]);
            return;
        }

        foreach ($adminUsers as $admin) {
            Mail::to($admin->email)->send(new LowStockAlert($this->product));
        }

        Log::info('Low stock notification sent', [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'current_stock' => $this->product->stock,
            'threshold' => $this->product->low_stock_threshold,
            'admin_count' => $adminUsers->count(),
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Failed to send low stock notification', [
            'product_id' => $this->product->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
