<?php

namespace App\Services;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    /**
     * Get featured products for homepage (products with sale prices)
     */
    public function getTopDeals()
    {
        return Product::with(['brand:id,name', 'categories:id,name'])
            ->where('status', ProductStatus::ACTIVE)
            ->whereNotNull('sale_price')
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get top electronics products
     */
    public function getTopElectronics()
    {
        $electronicsCategory = Category::where('slug', 'electronics')->first();

        return Product::with(['brand:id,name'])
            ->where('status', ProductStatus::ACTIVE)
            ->where('stock', '>', 0)
            ->when($electronicsCategory, function($q) use ($electronicsCategory) {
                $q->whereHas('categories', function($cq) use ($electronicsCategory) {
                    $cq->where('categories.id', $electronicsCategory->id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Search products with filters
     */
    public function searchProducts(array $validated, $page = 1)
    {
        $query = $validated['query'] ?? '';
        $perPage = $validated['per_page'] ?? 30;
        $sortBy = $validated['sort_by'] ?? 'newest';

        $cacheKey = 'product_search_' . md5(json_encode($validated) . '_page_' . $page);

        if (empty($query) || strlen($query) <= 3) {
            $cachedResult = Cache::get($cacheKey);
            if ($cachedResult) {
                return $cachedResult;
            }
        }

        $productsQuery = Product::query()
            ->with(['brand:id,name', 'categories:id,name'])
            ->where('status', ProductStatus::ACTIVE);

        // Search by name, description, sku
        if (!empty($query)) {
            $productsQuery->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('sku', 'LIKE', "%{$query}%");
            });
        }

        // Filter by category
        if (!empty($validated['category_id'])) {
            $productsQuery->whereHas('categories', function($q) use ($validated) {
                $q->where('categories.id', $validated['category_id']);
            });
        }

        // Filter by brand
        if (!empty($validated['brand_id'])) {
            $productsQuery->where('brand_id', $validated['brand_id']);
        }

        // Filter by price range
        if (!empty($validated['min_price'])) {
            $productsQuery->whereRaw('COALESCE(sale_price, price) >= ?', [$validated['min_price']]);
        }
        if (!empty($validated['max_price'])) {
            $productsQuery->whereRaw('COALESCE(sale_price, price) <= ?', [$validated['max_price']]);
        }

        // Filter by stock
        if (!empty($validated['in_stock'])) {
            $productsQuery->where('stock', '>', 0);
        }

        // Sorting
        switch ($sortBy) {
            case 'price_asc':
                $productsQuery->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_desc':
                $productsQuery->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'newest':
            default:
                $productsQuery->orderByDesc('created_at');
                break;
        }

        $products = $productsQuery->paginate($perPage);

        $response = [
            'data' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
            ],
        ];

        if (empty($query) || strlen($query) <= 3) {
            Cache::put($cacheKey, $response, now()->addMinutes(5));
        }

        return $response;
    }

    /**
     * Get products with filters for listing page
     */
    public function getProductsWithFilters($request)
    {
        $query = Product::with(['brand:id,name', 'categories:id,name'])
            ->where('status', ProductStatus::ACTIVE);

        // Apply search query
        if ($request->has('query') && !empty($request->get('query'))) {
            $searchQuery = $request->get('query');
            $query->where(function($q) use ($searchQuery) {
                $q->where('name', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('sku', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('description', 'LIKE', "%{$searchQuery}%");
            });
        }

        // Apply section-based filters
        $section = $request->get('section', 'all');
        switch ($section) {
            case 'top-deals':
                $query->whereNotNull('sale_price')
                    ->where('stock', '>', 0);
                break;
            case 'new-arrivals':
                $query->where('created_at', '>=', now()->subDays(30))
                    ->orderBy('created_at', 'desc');
                break;
        }

        // Filter by category
        if ($request->has('category') && !empty($request->get('category'))) {
            $categorySlug = $request->get('category');
            $query->whereHas('categories', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Filter by brand
        if ($request->has('brand') && !empty($request->get('brand'))) {
            $query->where('brand_id', $request->get('brand'));
        }

        // Filter by price
        if ($request->has('min_price')) {
            $query->whereRaw('COALESCE(sale_price, price) >= ?', [$request->get('min_price')]);
        }
        if ($request->has('max_price')) {
            $query->whereRaw('COALESCE(sale_price, price) <= ?', [$request->get('max_price')]);
        }

        // In stock filter
        if ($request->has('in_stock') && $request->get('in_stock')) {
            $query->where('stock', '>', 0);
        }

        // Sorting
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query->paginate(24);
    }

    /**
     * Get single product by slug
     */
    public function getProductBySlug($slug)
    {
        return Product::with(['brand:id,name', 'categories:id,name,slug'])
            ->where('slug', $slug)
            ->where('status', ProductStatus::ACTIVE)
            ->firstOrFail();
    }

    /**
     * Get related products (same category)
     */
    public function getRelatedProducts($product)
    {
        return Product::with(['brand:id,name', 'categories:id,name'])
            ->where('status', ProductStatus::ACTIVE)
            ->where('stock', '>', 0)
            ->where('id', '!=', $product->id)
            ->whereHas('categories', function($q) use ($product) {
                $q->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->limit(8)
            ->get();
    }
}
