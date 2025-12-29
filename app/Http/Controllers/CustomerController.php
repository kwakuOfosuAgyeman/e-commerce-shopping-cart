<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\PlaceOrderRequest;
use App\Http\Requests\Product\SearchProductsRequest;
use App\Models\Category;
use App\Models\Brand;
use App\Services\ProductService;
use App\Services\CheckoutService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected CheckoutService $checkoutService,
        protected OrderService $orderService
    ) {}

    /**
     * Display homepage
     */
    public function index()
    {
        $topDeals = $this->productService->getTopDeals();
        $topElectronics = $this->productService->getTopElectronics();

        $categories = Category::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('home', compact('topDeals', 'topElectronics', 'categories'));
    }

    /**
     * Search products API endpoint
     */
    public function searchProducts(SearchProductsRequest $request)
    {
        try {
            $validated = $request->validated();

            $response = $this->productService->searchProducts($validated, $request->input('page', 1));

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while searching products'
            ], 500);
        }
    }

    /**
     * Display product listing page
     */
    public function products(Request $request)
    {
        $products = $this->productService->getProductsWithFilters($request);

        $categories = Category::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $brands = Brand::orderBy('name')
            ->get();

        $section = $request->get('section', 'all');
        $pageTitle = $this->getPageTitle($request, $section);

        return view('products.index', compact('products', 'categories', 'brands', 'pageTitle', 'section'));
    }

    /**
     * Display single product detail page
     */
    public function showProduct($slug)
    {
        $product = $this->productService->getProductBySlug($slug);
        $relatedProducts = $this->productService->getRelatedProducts($product);

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Display checkout page
     */
    public function checkout()
    {
        $checkoutData = $this->checkoutService->getCheckoutData();

        if (empty($checkoutData['cartItems']) || $checkoutData['cartItems']->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }

        return view('checkout', [
            'cartItems' => $checkoutData['cartItems'],
            'subtotal' => $checkoutData['subtotal'],
            'total' => $checkoutData['total'],
        ]);
    }

    /**
     * Display user's orders
     */
    public function orders()
    {
        $orders = $this->orderService->getUserOrders();
        return view('orders.index', compact('orders'));
    }

    /**
     * Track specific order
     */
    public function trackOrder($id)
    {
        $order = $this->orderService->trackOrder($id);

        if (!$order) {
            return redirect()->route('user.orders')->with('error', 'Order not found');
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Cancel order
     */
    public function cancelOrder(Request $request, $id)
    {
        $result = $this->orderService->cancelOrder($id, $request);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('order.track', $result['order']->id)
            ->with('success', $result['message']);
    }

    /**
     * Place order from checkout
     */
    public function placeOrder(PlaceOrderRequest $request)
    {
        $validated = $request->validated();

        $result = $this->orderService->placeOrder($validated);

        if ($request->wantsJson()) {
            return response()->json($result, $result['code']);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('order.track', $result['order_id'])
            ->with('success', 'Order placed successfully!');
    }

    /**
     * Get page title based on request
     */
    private function getPageTitle(Request $request, string $section): string
    {
        if ($request->has('query') && !empty($request->get('query'))) {
            return 'Search Results for "' . $request->get('query') . '"';
        }

        $titles = [
            'top-deals' => 'Top Deals',
            'new-arrivals' => 'New Arrivals',
        ];

        return $titles[$section] ?? 'All Products';
    }
}
