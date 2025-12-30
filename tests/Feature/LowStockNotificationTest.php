<?php

namespace Tests\Feature;

use App\Jobs\SendLowStockNotification;
use App\Mail\LowStockAlert;
use App\Models\Product;
use App\Models\User;
use App\Observers\ProductObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class LowStockNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure the observer is registered
        Product::observe(ProductObserver::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Job Dispatching Tests
    |--------------------------------------------------------------------------
    |
    | These tests verify that the low stock notification job is dispatched
    | when a product's stock falls below the threshold.
    |
    */

    public function test_low_stock_job_is_dispatched_when_stock_drops_below_threshold(): void
    {
        Queue::fake();

        $product = Product::factory()->create([
            'stock' => 15,
            'low_stock_threshold' => 10,
        ]);

        // Reduce stock below threshold
        $product->update(['stock' => 5]);

        Queue::assertPushed(SendLowStockNotification::class, function ($job) use ($product) {
            return $job->product->id === $product->id;
        });
    }

    public function test_low_stock_job_is_not_dispatched_when_stock_remains_above_threshold(): void
    {
        Queue::fake();

        $product = Product::factory()->create([
            'stock' => 50,
            'low_stock_threshold' => 10,
        ]);

        // Reduce stock but still above threshold
        $product->update(['stock' => 25]);

        Queue::assertNotPushed(SendLowStockNotification::class);
    }

    public function test_low_stock_job_is_not_dispatched_when_stock_already_below_threshold(): void
    {
        Queue::fake();

        $product = Product::factory()->create([
            'stock' => 5,
            'low_stock_threshold' => 10,
        ]);

        // Further reduce stock (already below threshold)
        $product->update(['stock' => 3]);

        // Should not trigger again as we didn't cross the threshold
        Queue::assertNotPushed(SendLowStockNotification::class);
    }

    public function test_low_stock_job_uses_custom_threshold(): void
    {
        Queue::fake();

        $product = Product::factory()->create([
            'stock' => 25,
            'low_stock_threshold' => 20, // Custom higher threshold
        ]);

        // Reduce below custom threshold
        $product->update(['stock' => 15]);

        Queue::assertPushed(SendLowStockNotification::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Job Execution Tests
    |--------------------------------------------------------------------------
    |
    | These tests verify that the job correctly sends emails to admin users.
    |
    */

    public function test_low_stock_job_sends_email_to_admin_users(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $product = Product::factory()->lowStock()->create();

        $job = new SendLowStockNotification($product);
        $job->handle();

        Mail::assertSent(LowStockAlert::class, function ($mail) use ($admin, $product) {
            return $mail->hasTo($admin->email) &&
                   $mail->product->id === $product->id;
        });
    }

    public function test_low_stock_job_sends_email_to_all_admin_users(): void
    {
        Mail::fake();

        $admin1 = User::factory()->create(['is_admin' => true]);
        $admin2 = User::factory()->create(['is_admin' => true]);
        $regularUser = User::factory()->create(['is_admin' => false]);

        $product = Product::factory()->lowStock()->create();

        $job = new SendLowStockNotification($product);
        $job->handle();

        // Should send to both admins
        Mail::assertSent(LowStockAlert::class, 2);

        Mail::assertSent(LowStockAlert::class, function ($mail) use ($admin1) {
            return $mail->hasTo($admin1->email);
        });

        Mail::assertSent(LowStockAlert::class, function ($mail) use ($admin2) {
            return $mail->hasTo($admin2->email);
        });

        // Should not send to regular user
        Mail::assertNotSent(LowStockAlert::class, function ($mail) use ($regularUser) {
            return $mail->hasTo($regularUser->email);
        });
    }

    public function test_low_stock_job_does_not_send_if_no_admin_users(): void
    {
        Mail::fake();

        // Only regular users exist
        User::factory()->create(['is_admin' => false]);

        $product = Product::factory()->lowStock()->create();

        $job = new SendLowStockNotification($product);
        $job->handle();

        Mail::assertNothingSent();
    }

    public function test_low_stock_job_skips_if_stock_replenished_before_execution(): void
    {
        Mail::fake();

        User::factory()->create(['is_admin' => true]);

        $product = Product::factory()->create([
            'stock' => 5,
            'low_stock_threshold' => 10,
        ]);

        // Create job
        $job = new SendLowStockNotification($product);

        // Replenish stock before job executes
        $product->update(['stock' => 50]);

        // Execute job
        $job->handle();

        // Should not send email since stock is now above threshold
        Mail::assertNothingSent();
    }

    /*
    |--------------------------------------------------------------------------
    | Job Retry Configuration Tests
    |--------------------------------------------------------------------------
    */

    public function test_low_stock_job_has_correct_retry_configuration(): void
    {
        $product = Product::factory()->lowStock()->create();
        $job = new SendLowStockNotification($product);

        $this->assertEquals(5, $job->tries);
        $this->assertEquals([10, 30, 60, 120, 300], $job->backoff());
    }

    public function test_low_stock_job_has_retry_until_set(): void
    {
        $product = Product::factory()->lowStock()->create();
        $job = new SendLowStockNotification($product);

        $retryUntil = $job->retryUntil();

        // Should be approximately 4 hours from now
        $this->assertGreaterThan(now()->addHours(3), $retryUntil);
        $this->assertLessThan(now()->addHours(5), $retryUntil);
    }

    /*
    |--------------------------------------------------------------------------
    | Integration Tests with Product Updates
    |--------------------------------------------------------------------------
    */

    public function test_decrementing_stock_triggers_low_stock_notification(): void
    {
        Queue::fake();

        $product = Product::factory()->create([
            'stock' => 15,
            'low_stock_threshold' => 10,
        ]);

        // Decrement stock to below threshold
        $product->decrement('stock', 10);

        Queue::assertPushed(SendLowStockNotification::class);
    }

    public function test_low_stock_email_contains_product_information(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $product = Product::factory()->create([
            'name' => 'Test Product XYZ',
            'stock' => 3,
            'low_stock_threshold' => 10,
            'sku' => 'TEST-SKU-123',
        ]);

        $job = new SendLowStockNotification($product);
        $job->handle();

        Mail::assertSent(LowStockAlert::class, function ($mail) use ($product) {
            return $mail->product->name === 'Test Product XYZ' &&
                   $mail->product->stock === 3 &&
                   $mail->product->sku === 'TEST-SKU-123';
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Partial Failure Tests
    |--------------------------------------------------------------------------
    */

    public function test_low_stock_job_continues_if_some_emails_fail(): void
    {
        Mail::fake();

        $admin1 = User::factory()->create(['is_admin' => true, 'email' => 'admin1@test.com']);
        $admin2 = User::factory()->create(['is_admin' => true, 'email' => 'admin2@test.com']);

        $product = Product::factory()->lowStock()->create();

        // Make first email fail
        Mail::shouldReceive('to')
            ->with($admin1->email)
            ->andThrow(new \Exception('Email failed'));

        Mail::shouldReceive('to')
            ->with($admin2->email)
            ->andReturnSelf();

        Mail::shouldReceive('send')
            ->andReturn(null);

        $job = new SendLowStockNotification($product);

        // Job should not throw exception (partial success)
        $job->handle();

        $this->assertTrue(true); // If we get here, the job handled partial failure correctly
    }
}
