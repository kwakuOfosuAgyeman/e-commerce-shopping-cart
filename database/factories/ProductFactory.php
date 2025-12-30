<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(5),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 500),
            'sku' => strtoupper(Str::random(8)),
            'stock' => fake()->numberBetween(10, 100),
            'low_stock_threshold' => 10,
            'status' => ProductStatus::ACTIVE,
            'currency' => 'USD',
            'brand_id' => null,
        ];
    }

    /**
     * Indicate the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    /**
     * Indicate the product has low stock.
     */
    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => fake()->numberBetween(1, 5),
            'low_stock_threshold' => 10,
        ]);
    }

    /**
     * Indicate the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProductStatus::INACTIVE,
        ]);
    }

    /**
     * Indicate the product is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProductStatus::DRAFT,
        ]);
    }
}
