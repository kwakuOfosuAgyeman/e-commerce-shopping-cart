<x-app-layout>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-72 h-72 bg-white opacity-10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white opacity-10 rounded-full translate-x-1/3 translate-y-1/3"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight">
                    Discover Amazing
                    <span class="block mt-2 bg-gradient-to-r from-yellow-200 to-yellow-400 bg-clip-text text-transparent">
                        Products
                    </span>
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-lg md:text-xl text-indigo-100">
                    Shop the latest trends with unbeatable prices. Quality products delivered to your doorstep.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-indigo-600 bg-white rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <span>Shop Now</span>
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    <a href="{{ route('products.index', ['section' => 'new-arrivals']) }}"
                       class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-white border-2 border-white/30 rounded-xl hover:bg-white/10 transition-all duration-200">
                        New Arrivals
                    </a>
                </div>
            </div>
        </div>
        <!-- Wave decoration -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#f3f4f6"/>
            </svg>
        </div>
    </div>

    <div class="bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <!-- Features Strip -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-16">
                <div class="bg-white rounded-2xl p-6 flex items-center space-x-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Free Shipping</h3>
                        <p class="text-sm text-gray-500">On orders over $50</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 flex items-center space-x-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Secure Payment</h3>
                        <p class="text-sm text-gray-500">100% protected</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 flex items-center space-x-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-shrink-0 w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Easy Returns</h3>
                        <p class="text-sm text-gray-500">30-day returns</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 flex items-center space-x-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">24/7 Support</h3>
                        <p class="text-sm text-gray-500">Here to help</p>
                    </div>
                </div>
            </div>

            <!-- Categories Section -->
            @if($categories->count() > 0)
                <div class="mb-16">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Shop by Category</h2>
                            <p class="mt-2 text-gray-500">Browse our curated collection</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                        @foreach($categories as $category)
                            <a href="{{ route('products.index', ['category' => $category->id]) }}"
                               class="group relative bg-white rounded-2xl p-8 text-center shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="relative">
                                    @if($category->svg)
                                        <div class="w-16 h-16 mx-auto mb-4 text-indigo-600 group-hover:text-purple-600 transition-colors">
                                            {!! $category->svg !!}
                                        </div>
                                    @else
                                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl flex items-center justify-center group-hover:from-indigo-200 group-hover:to-purple-200 transition-colors">
                                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $category->name }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ $category->products_count ?? 'Explore' }} products</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Top Deals Section -->
            @if($topDeals->count() > 0)
                <div class="mb-16">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Top Deals</h2>
                            <p class="mt-2 text-gray-500">Handpicked products just for you</p>
                        </div>
                        <a href="{{ route('products.index', ['section' => 'top-deals']) }}"
                           class="hidden sm:inline-flex items-center text-indigo-600 hover:text-indigo-700 font-semibold group">
                            View All
                            <svg class="ml-2 w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
                        @foreach($topDeals as $product)
                            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                                <a href="{{ route('products.show', $product->slug) }}" class="block">
                                    <div class="relative aspect-square bg-gray-100 overflow-hidden">
                                        @if($product->primaryImage)
                                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                                 alt="{{ $product->name }}"
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        @if($product->stock <= 5 && $product->stock > 0)
                                            <div class="absolute top-3 left-3">
                                                <span class="px-2 py-1 bg-orange-500 text-white text-xs font-semibold rounded-lg">Low Stock</span>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    </div>
                                </a>
                                <div class="p-4">
                                    @if($product->brand)
                                        <p class="text-xs font-medium text-indigo-600 mb-1">{{ $product->brand->name }}</p>
                                    @endif
                                    <a href="{{ route('products.show', $product->slug) }}"
                                       class="block font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2 min-h-[2.5rem]">
                                        {{ $product->name }}
                                    </a>
                                    <div class="mt-3 flex items-center justify-between">
                                        <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                        @if($product->stock > 0)
                                            <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">In Stock</span>
                                        @else
                                            <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">Sold Out</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 text-center sm:hidden">
                        <a href="{{ route('products.index', ['section' => 'top-deals']) }}"
                           class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-semibold">
                            View All Deals
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Top Electronics Section -->
            @if($topElectronics->count() > 0)
                <div class="mb-16">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Top Electronics</h2>
                            <p class="mt-2 text-gray-500">Latest gadgets and tech</p>
                        </div>
                        @php $electronicsCategory = $categories->firstWhere('name', 'Electronics'); @endphp
                        @if($electronicsCategory)
                            <a href="{{ route('products.index', ['category' => $electronicsCategory->id]) }}"
                               class="hidden sm:inline-flex items-center text-indigo-600 hover:text-indigo-700 font-semibold group">
                                View All
                                <svg class="ml-2 w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
                        @foreach($topElectronics as $product)
                            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                                <a href="{{ route('products.show', $product->slug) }}" class="block">
                                    <div class="relative aspect-square bg-gray-100 overflow-hidden">
                                        @if($product->primaryImage)
                                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                                 alt="{{ $product->name }}"
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    </div>
                                </a>
                                <div class="p-4">
                                    @if($product->brand)
                                        <p class="text-xs font-medium text-indigo-600 mb-1">{{ $product->brand->name }}</p>
                                    @endif
                                    <a href="{{ route('products.show', $product->slug) }}"
                                       class="block font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2 min-h-[2.5rem]">
                                        {{ $product->name }}
                                    </a>
                                    <div class="mt-3 flex items-center justify-between">
                                        <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                        @if($product->stock > 0)
                                            <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">In Stock</span>
                                        @else
                                            <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">Sold Out</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Newsletter Section -->
            <div class="relative bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl overflow-hidden">
                <div class="absolute inset-0">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full translate-x-1/3 -translate-y-1/2"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -translate-x-1/3 translate-y-1/2"></div>
                </div>
                <div class="relative px-6 py-12 md:px-12 md:py-16 text-center">
                    <h3 class="text-2xl md:text-3xl font-bold text-white">Stay in the Loop</h3>
                    <p class="mt-3 text-indigo-100 max-w-xl mx-auto">Subscribe to get special offers, free giveaways, and exclusive deals.</p>
                    <form class="mt-8 max-w-md mx-auto flex flex-col sm:flex-row gap-3">
                        <input type="email" placeholder="Enter your email"
                               class="flex-1 px-5 py-3 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white/50">
                        <button type="submit"
                                class="px-8 py-3 bg-white text-indigo-600 font-semibold rounded-xl hover:bg-gray-100 transition-colors shadow-lg">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>

            <!-- Empty State -->
            @if($topDeals->count() == 0 && $topElectronics->count() == 0)
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                    <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">No products available</h3>
                    <p class="mt-2 text-gray-500">Check back soon for new arrivals and deals.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">ShopHub</span>
                    </div>
                    <p class="text-sm">Your one-stop shop for amazing products at unbeatable prices.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Shop</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors">All Products</a></li>
                        <li><a href="{{ route('products.index', ['section' => 'new-arrivals']) }}" class="hover:text-white transition-colors">New Arrivals</a></li>
                        <li><a href="{{ route('products.index', ['section' => 'top-deals']) }}" class="hover:text-white transition-colors">Top Deals</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Account</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Login</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Register</a></li>
                        @auth
                            <li><a href="{{ route('user.orders') }}" class="hover:text-white transition-colors">Orders</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Support</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Shipping Info</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-sm text-center">
                <p>&copy; {{ date('Y') }} ShopHub. All rights reserved.</p>
            </div>
        </div>
    </footer>
</x-app-layout>
