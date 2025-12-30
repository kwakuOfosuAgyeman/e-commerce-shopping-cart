<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Jobs\SendDailySalesReport;
use App\Mail\DailySalesReport;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class DailySalesReportTest extends TestCase
{
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | Job Execution Tests
    |--------------------------------------------------------------------------
    |
    | These tests verify that the daily sales report job correctly sends
    | emails to admin users with accurate sales data.
    |
    */

    public function test_daily_sales_report_sends_email_to_admin_users(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create(['is_admin' => false]);
        $product = Product::factory()->create(['price' => 100]);

        // Create order for today
        $order = Order::factory()->forUser($customer)->create([
            'total_amount' => 200,
            'created_at' => now(),
        ]);

        OrderItem::factory()->forOrder($order)->forProduct($product)->create([
            'quantity' => 2,
            'price' => 100,
        ]);

        $job = new SendDailySalesReport(Carbon::today());
        $job->handle();

        Mail::assertSent(DailySalesReport::class, function ($mail) use ($admin) {
            return $mail->hasTo($admin->email);
        });
    }

    public function test_daily_sales_report_sends_to_all_admin_users(): void
    {
        Mail::fake();

        $admin1 = User::factory()->create(['is_admin' => true]);
        $admin2 = User::factory()->create(['is_admin' => true]);
        $regularUser = User::factory()->create(['is_admin' => false]);

        $job = new SendDailySalesReport(Carbon::today());
        $job->handle();

        Mail::assertSent(DailySalesReport::class, 2);

        Mail::assertSent(DailySalesReport::class, function ($mail) use ($admin1) {
            return $mail->hasTo($admin1->email);
        });

        Mail::assertSent(DailySalesReport::class, function ($mail) use ($admin2) {
            return $mail->hasTo($admin2->email);
        });

        // Regular user should not receive the report
        Mail::assertNotSent(DailySalesReport::class, function ($mail) use ($regularUser) {
            return $mail->hasTo($regularUser->email);
        });
    }

    public function test_daily_sales_report_does_not_send_if_no_admin_users(): void
    {
        Mail::fake();

        // Only regular users exist
        User::factory()->create(['is_admin' => false]);

        $job = new SendDailySalesReport(Carbon::today());
        $job->handle();

        Mail::assertNothingSent();
    }

    /*
    |--------------------------------------------------------------------------
    | Report Data Tests
    |--------------------------------------------------------------------------
    |
    | These tests verify that the report contains accurate sales data.
    |
    */

    public function test_daily_sales_report_includes_only_todays_orders(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create(['is_admin' => false]);
        $product = Product::factory()->create(['price' => 50]);

        // Order from today
        $todayOrder = Order::factory()->forUser($customer)->create([
            'total_amount' => 100,
            'created_at' => now(),
        ]);

        OrderItem::factory()->forOrder($todayOrder)->forProduct($product)->create([
            'quantity' => 2,
            'price' => 50,
        ]);

        // Order from yesterday (should NOT be included)
        $yesterdayOrder = Order::factory()->forUser($customer)->create([
            'total_amount' => 500,
            'created_at' => now()->subDay(),
        ]);

        OrderItem::factory()->forOrder($yesterdayOrder)->forProduct($product)->create([
            'quantity' => 10,
            'price' => 50,
        ]);

        $job = new SendDailySalesReport(Carbon::today());
        $job->handle();

        Mail::assertSent(DailySalesReport::class, function ($mail) {
            // Report should only have 1 order and $100 revenue
            return $mail->reportData['total_orders'] === 1 &&
                   $mail->reportData['total_revenue'] == 100;
        });
    }

    public function test_daily_sales_report_calculates_correct_totals(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create(['is_admin' => false]);
        $product1 = Product::factory()->create(['price' => 100]);
        $product2 = Product::factory()->create(['price' => 50]);

        // Create multiple orders for today
        $order1 = Order::factory()->forUser($customer)->create([
            'total_amount' => 200,
            'created_at' => now(),
        ]);

        OrderItem::factory()->forOrder($order1)->forProduct($product1)->create([
            'quantity' => 2,
            'price' => 100,
        ]);

        $order2 = Order::factory()->forUser($customer)->create([
            'total_amount' => 150,
            'created_at' => now(),
        ]);

        OrderItem::factory()->forOrder($order2)->forProduct($product2)->create([
            'quantity' => 3,
            'price' => 50,
        ]);

        $job = new SendDailySalesReport(Carbon::today());
        $job->handle();

        Mail::assertSent(DailySalesReport::class, function ($mail) {
            return $mail->reportData['total_orders'] === 2 &&
                   $mail->reportData['total_revenue'] == 350 && // 200 + 150
                   $mail->reportData['total_items_sold'] === 5; // 2 + 3
        });
    }

    public function test_daily_sales_report_includes_status_breakdown(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create(['is_admin' => false]);

        // Create orders with different statuses
        Order::factory()->forUser($customer)->create([
            'status' => OrderStatus::PENDING,
            'created_at' => now(),
        ]);

        Order::factory()->forUser($customer)->create([
            'status' => OrderStatus::PENDING,
            'created_at' => now(),
        ]);

        Order::factory()->forUser($customer)->create([
            'status' => OrderStatus::CANCELLED,
            'created_at' => now(),
        ]);

        $job = new SendDailySalesReport(Carbon::today());
        $job->handle();

        Mail::assertSent(DailySalesReport::class, function ($mail) {
            $breakdown = $mail->reportData['status_breakdown'];
            return isset($breakdown[OrderStatus::PENDING->value]) &&
                   $breakdown[OrderStatus::PENDING->value] === 2 &&
                   isset($breakdown[OrderStatus::CANCELLED->value]) &&
                   $breakdown[OrderStatus::CANCELLED->value] === 1;
        });
    }

    public function test_daily_sales_report_calculates_average_order_value(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create(['is_admin' => false]);

        Order::factory()->forUser($customer)->create([
            'total_amount' => 100,
            'created_at' => now(),
        ]);

        Order::factory()->forUser($customer)->create([
            'total_amount' => 200,
            'created_at' => now(),
        ]);

        Order::factory()->forUser($customer)->create([
            'total_amount' => 300,
            'created_at' => now(),
        ]);

        $job = new SendDailySalesReport(Carbon::today());
        $job->handle();

        Mail::assertSent(DailySalesReport::class, function ($mail) {
            // Average should be (100 + 200 + 300) / 3 = 200
            return $mail->reportData['average_order_value'] == 200;
        });
    }

    public function test_daily_sales_report_handles_no_orders(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['is_admin' => true]);

        // No orders exist

        $job = new SendDailySalesReport(Carbon::today());
        $job->handle();

        Mail::assertSent(DailySalesReport::class, function ($mail) {
            return $mail->reportData['total_orders'] === 0 &&
                   $mail->reportData['total_revenue'] == 0 &&
                   $mail->reportData['average_order_value'] == 0;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Scheduling Tests
    |--------------------------------------------------------------------------
    |
    | These tests verify that the job can be scheduled correctly.
    |
    */

    public function test_daily_sales_report_can_be_queued(): void
    {
        Queue::fake();

        SendDailySalesReport::dispatch(Carbon::today());

        Queue::assertPushed(SendDailySalesReport::class, function ($job) {
            return $job->reportDate->toDateString() === Carbon::today()->toDateString();
        });
    }

    public function test_daily_sales_report_can_generate_for_specific_date(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create(['is_admin' => false]);

        $specificDate = Carbon::create(2025, 1, 15);

        // Create order for specific date
        Order::factory()->forUser($customer)->create([
            'total_amount' => 500,
            'created_at' => $specificDate->copy()->setTime(14, 30),
        ]);

        // Create order for different date (should not be included)
        Order::factory()->forUser($customer)->create([
            'total_amount' => 1000,
            'created_at' => $specificDate->copy()->addDay(),
        ]);

        $job = new SendDailySalesReport($specificDate);
        $job->handle();

        Mail::assertSent(DailySalesReport::class, function ($mail) use ($specificDate) {
            return $mail->reportDate->toDateString() === $specificDate->toDateString() &&
                   $mail->reportData['total_orders'] === 1 &&
                   $mail->reportData['total_revenue'] == 500;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Job Configuration Tests
    |--------------------------------------------------------------------------
    */

    public function test_daily_sales_report_job_has_correct_retry_configuration(): void
    {
        $job = new SendDailySalesReport(Carbon::today());

        $this->assertEquals(5, $job->tries);
        $this->assertEquals([30, 60, 120, 300, 600], $job->backoff());
    }

    public function test_daily_sales_report_job_has_retry_until_set(): void
    {
        $job = new SendDailySalesReport(Carbon::today());

        $retryUntil = $job->retryUntil();

        // Should be approximately 6 hours from now
        $this->assertGreaterThan(now()->addHours(5), $retryUntil);
        $this->assertLessThan(now()->addHours(7), $retryUntil);
    }

    public function test_daily_sales_report_defaults_to_today_if_no_date_provided(): void
    {
        $job = new SendDailySalesReport();

        $this->assertEquals(Carbon::today()->toDateString(), $job->reportDate->toDateString());
    }

    /*
    |--------------------------------------------------------------------------
    | Product Sales Summary Tests
    |--------------------------------------------------------------------------
    */

    public function test_daily_sales_report_includes_product_sales_summary(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create(['is_admin' => false]);

        $product1 = Product::factory()->create(['name' => 'Product A', 'price' => 100]);
        $product2 = Product::factory()->create(['name' => 'Product B', 'price' => 50]);

        $order = Order::factory()->forUser($customer)->create([
            'total_amount' => 250,
            'created_at' => now(),
        ]);

        // Product A: 2 units at $100 = $200
        OrderItem::factory()->forOrder($order)->forProduct($product1)->create([
            'quantity' => 2,
            'price' => 100,
        ]);

        // Product B: 1 unit at $50 = $50
        OrderItem::factory()->forOrder($order)->forProduct($product2)->create([
            'quantity' => 1,
            'price' => 50,
        ]);

        $job = new SendDailySalesReport(Carbon::today());
        $job->handle();

        Mail::assertSent(DailySalesReport::class, function ($mail) {
            $productSales = $mail->reportData['product_sales'];

            // Should have 2 products in summary
            return $productSales->count() === 2;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Edge Cases
    |--------------------------------------------------------------------------
    */

    public function test_daily_sales_report_handles_orders_at_day_boundaries(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create(['is_admin' => false]);

        $today = Carbon::today();

        // Order at start of day (00:00:01)
        Order::factory()->forUser($customer)->create([
            'total_amount' => 100,
            'created_at' => $today->copy()->startOfDay()->addSecond(),
        ]);

        // Order at end of day (23:59:59)
        Order::factory()->forUser($customer)->create([
            'total_amount' => 200,
            'created_at' => $today->copy()->endOfDay()->subSecond(),
        ]);

        // Order just after midnight (should NOT be included - next day)
        Order::factory()->forUser($customer)->create([
            'total_amount' => 1000,
            'created_at' => $today->copy()->addDay()->startOfDay()->addSecond(),
        ]);

        $job = new SendDailySalesReport($today);
        $job->handle();

        Mail::assertSent(DailySalesReport::class, function ($mail) {
            return $mail->reportData['total_orders'] === 2 &&
                   $mail->reportData['total_revenue'] == 300;
        });
    }
}
