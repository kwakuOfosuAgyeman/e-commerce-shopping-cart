<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Seed sample products with categories and brands.
     */
    public function run(): void
    {
        // Create brands
        $brands = [
            ['name' => 'TechPro', 'slug' => 'techpro', 'description' => 'Premium tech accessories', 'is_active' => true],
            ['name' => 'HomeEssentials', 'slug' => 'home-essentials', 'description' => 'Quality home products', 'is_active' => true],
            ['name' => 'StyleMax', 'slug' => 'stylemax', 'description' => 'Fashion and lifestyle', 'is_active' => true],
        ];

        foreach ($brands as $brandData) {
            Brand::updateOrCreate(['slug' => $brandData['slug']], $brandData);
        }

        // Create categories
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Electronic devices and gadgets', 'is_active' => true],
            ['name' => 'Clothing', 'slug' => 'clothing', 'description' => 'Apparel and fashion items', 'is_active' => true],
            ['name' => 'Home & Garden', 'slug' => 'home-garden', 'description' => 'Home improvement and garden items', 'is_active' => true],
            ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports equipment and accessories', 'is_active' => true],
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(['slug' => $categoryData['slug']], $categoryData);
        }

        // Get created entities
        $techBrand = Brand::where('slug', 'techpro')->first();
        $homeBrand = Brand::where('slug', 'home-essentials')->first();
        $styleBrand = Brand::where('slug', 'stylemax')->first();

        $electronicsCategory = Category::where('slug', 'electronics')->first();
        $clothingCategory = Category::where('slug', 'clothing')->first();
        $homeCategory = Category::where('slug', 'home-garden')->first();
        $sportsCategory = Category::where('slug', 'sports')->first();

        // Sample products
        $products = [
            [
                'name' => 'Wireless Bluetooth Headphones',
                'slug' => 'wireless-bluetooth-headphones',
                'description' => 'High-quality wireless headphones with noise cancellation and 30-hour battery life.',
                'price' => 79.99,
                'stock' => 50,
                'low_stock_threshold' => 10,
                'sku' => 'TECH-001',
                'status' => 'active',
                'brand_id' => $techBrand?->id,
                'category_id' => $electronicsCategory?->id,
            ],
            [
                'name' => 'Smart Watch Pro',
                'slug' => 'smart-watch-pro',
                'description' => 'Feature-packed smartwatch with heart rate monitor, GPS, and water resistance.',
                'price' => 199.99,
                'stock' => 30,
                'low_stock_threshold' => 5,
                'sku' => 'TECH-002',
                'status' => 'active',
                'brand_id' => $techBrand?->id,
                'category_id' => $electronicsCategory?->id,
            ],
            [
                'name' => 'USB-C Charging Cable (3-Pack)',
                'slug' => 'usb-c-charging-cable-3pack',
                'description' => 'Durable braided USB-C cables, 6ft length, fast charging compatible.',
                'price' => 14.99,
                'stock' => 200,
                'low_stock_threshold' => 20,
                'sku' => 'TECH-003',
                'status' => 'active',
                'brand_id' => $techBrand?->id,
                'category_id' => $electronicsCategory?->id,
            ],
            [
                'name' => 'Cotton T-Shirt - Classic Fit',
                'slug' => 'cotton-tshirt-classic-fit',
                'description' => '100% premium cotton t-shirt, comfortable classic fit, available in multiple colors.',
                'price' => 24.99,
                'stock' => 100,
                'low_stock_threshold' => 15,
                'sku' => 'STYLE-001',
                'status' => 'active',
                'brand_id' => $styleBrand?->id,
                'category_id' => $clothingCategory?->id,
            ],
            [
                'name' => 'Denim Jeans - Slim Fit',
                'slug' => 'denim-jeans-slim-fit',
                'description' => 'Premium denim jeans with stretch comfort, modern slim fit design.',
                'price' => 59.99,
                'stock' => 75,
                'low_stock_threshold' => 10,
                'sku' => 'STYLE-002',
                'status' => 'active',
                'brand_id' => $styleBrand?->id,
                'category_id' => $clothingCategory?->id,
            ],
            [
                'name' => 'Stainless Steel Water Bottle',
                'slug' => 'stainless-steel-water-bottle',
                'description' => 'Double-wall insulated water bottle, keeps drinks cold for 24hrs or hot for 12hrs.',
                'price' => 29.99,
                'stock' => 150,
                'low_stock_threshold' => 25,
                'sku' => 'HOME-001',
                'status' => 'active',
                'brand_id' => $homeBrand?->id,
                'category_id' => $homeCategory?->id,
            ],
            [
                'name' => 'LED Desk Lamp',
                'slug' => 'led-desk-lamp',
                'description' => 'Adjustable LED desk lamp with multiple brightness levels and USB charging port.',
                'price' => 39.99,
                'stock' => 60,
                'low_stock_threshold' => 8,
                'sku' => 'HOME-002',
                'status' => 'active',
                'brand_id' => $homeBrand?->id,
                'category_id' => $homeCategory?->id,
            ],
            [
                'name' => 'Yoga Mat - Premium',
                'slug' => 'yoga-mat-premium',
                'description' => 'Non-slip yoga mat with alignment marks, eco-friendly material, 6mm thickness.',
                'price' => 34.99,
                'stock' => 80,
                'low_stock_threshold' => 12,
                'sku' => 'SPORT-001',
                'status' => 'active',
                'brand_id' => $styleBrand?->id,
                'category_id' => $sportsCategory?->id,
            ],
            [
                'name' => 'Resistance Bands Set',
                'slug' => 'resistance-bands-set',
                'description' => 'Complete set of 5 resistance bands with different strength levels, includes carrying bag.',
                'price' => 19.99,
                'stock' => 120,
                'low_stock_threshold' => 15,
                'sku' => 'SPORT-002',
                'status' => 'active',
                'brand_id' => $styleBrand?->id,
                'category_id' => $sportsCategory?->id,
            ],
            [
                'name' => 'Portable Bluetooth Speaker',
                'slug' => 'portable-bluetooth-speaker',
                'description' => 'Waterproof portable speaker with 360° sound, 20-hour playtime.',
                'price' => 49.99,
                'stock' => 8,
                'low_stock_threshold' => 10,
                'sku' => 'TECH-004',
                'status' => 'active',
                'brand_id' => $techBrand?->id,
                'category_id' => $electronicsCategory?->id,
            ],
        ];

        foreach ($products as $productData) {
            $categoryId = $productData['category_id'];
            unset($productData['category_id']);

            $product = Product::updateOrCreate(
                ['sku' => $productData['sku']],
                $productData
            );

            // Attach category if exists
            if ($categoryId && $product->categories()->where('category_id', $categoryId)->doesntExist()) {
                $product->categories()->attach($categoryId);
            }
        }
    }
}
