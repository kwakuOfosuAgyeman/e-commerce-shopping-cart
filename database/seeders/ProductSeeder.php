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
            ['name' => 'TechPro', 'description' => 'Premium tech accessories'],
            ['name' => 'HomeEssentials', 'description' => 'Quality home products'],
            ['name' => 'StyleMax', 'description' => 'Fashion and lifestyle'],
        ];

        foreach ($brands as $brandData) {
            Brand::updateOrCreate(['name' => $brandData['name']], $brandData);
        }

        // Create categories with SVG icons
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and gadgets',
                'svg' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>',
            ],
            [
                'name' => 'Clothing',
                'description' => 'Apparel and fashion items',
                'svg' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>',
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Home improvement and garden items',
                'svg' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>',
            ],
            [
                'name' => 'Sports',
                'description' => 'Sports equipment and accessories',
                'svg' => '<svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(['name' => $categoryData['name']], $categoryData);
        }

        // Get created entities
        $techBrand = Brand::where('name', 'TechPro')->first();
        $homeBrand = Brand::where('name', 'HomeEssentials')->first();
        $styleBrand = Brand::where('name', 'StyleMax')->first();

        $electronicsCategory = Category::where('name', 'Electronics')->first();
        $clothingCategory = Category::where('name', 'Clothing')->first();
        $homeCategory = Category::where('name', 'Home & Garden')->first();
        $sportsCategory = Category::where('name', 'Sports')->first();

        // Sample products
        $products = [
            [
                'name' => 'Wireless Bluetooth Headphones',
                'slug' => 'wireless-bluetooth-headphones',
                'description' => 'High-quality wireless headphones with noise cancellation and 30-hour battery life.',
                'price' => 79.99,
                'currency' => 'USD',
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
                'currency' => 'USD',
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
                'currency' => 'USD',
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
                'currency' => 'USD',
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
                'currency' => 'USD',
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
                'currency' => 'USD',
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
                'currency' => 'USD',
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
                'currency' => 'USD',
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
                'currency' => 'USD',
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
                'currency' => 'USD',
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
