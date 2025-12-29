<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->mediumText('description');
            $table->decimal('price', 10, 2);
            $table->string('sku')->unique();
            $table->string('currency');
            $table->enum('status', ['active', 'inactive', 'draft'])->default('draft');
            $table->integer('stock');
            $table->string('slug')->unique();
            $table->integer('low_stock_threshold')->default(10);
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');

            $table->timestamps();

            // Indexes for better performance
            $table->index('sku');
            $table->index('status');
            $table->index('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
