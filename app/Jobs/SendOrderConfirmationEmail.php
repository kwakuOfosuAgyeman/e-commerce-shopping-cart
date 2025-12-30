<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendOrderConfirmationEmail implements ShouldQueue
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
    public int $timeout = 30;

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
        public Order $order,
        public OrderStatus $expectedStatus = OrderStatus::PENDING
    ) {}

    /**
     * Determine if the job should be retried based on the exception.
     */
    public function retryUntil(): \DateTime
    {
        return now()->addHours(2);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Refresh order from database to get current status
        $this->order->refresh();

        // Guard: Don't send if status has changed
        if ($this->order->status !== $this->expectedStatus) {
            Log::info('Skipping order confirmation email - status changed', [
                'order_id' => $this->order->id,
                'order_number' => $this->order->order_number,
                'expected_status' => $this->expectedStatus->value,
                'actual_status' => $this->order->status->value,
            ]);
            return;
        }

        // Guard: Ensure user exists and has email
        if (!$this->order->user || !$this->order->user->email) {
            Log::warning('Cannot send order confirmation - user or email missing', [
                'order_id' => $this->order->id,
                'order_number' => $this->order->order_number,
            ]);
            return;
        }

        // Send the email
        Mail::to($this->order->user->email)
            ->send(new OrderConfirmation($this->order));

        Log::info('Order confirmation email sent', [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'user_email' => $this->order->user->email,
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(?Throwable $exception): void
    {
        Log::error('Failed to send order confirmation email after all retries', [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'error' => $exception?->getMessage(),
            'attempts' => $this->attempts(),
        ]);
    }
}
