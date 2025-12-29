<div class="min-h-screen bg-gray-50">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold text-white">All Products</h1>
            <p class="mt-2 text-indigo-100">Discover our complete collection</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:w-72 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-24">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Filters</h3>

                    <!-- Search -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <div class="relative">
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   placeholder="Search products..."
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select wire:model.live="categoryId"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <div class="space-y-2">
                            <button wire:click="setSort('created_at')"
                                    class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl text-sm transition-colors {{ $sortBy === 'created_at' ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                                <span>Newest First</span>
                                @if($sortBy === 'created_at')
                                    <svg class="w-4 h-4 transform {{ $sortDirection === 'desc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            </button>
                            <button wire:click="setSort('price')"
                                    class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl text-sm transition-colors {{ $sortBy === 'price' ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                                <span>Price</span>
                                @if($sortBy === 'price')
                                    <svg class="w-4 h-4 transform {{ $sortDirection === 'desc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            </button>
                            <button wire:click="setSort('name')"
                                    class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl text-sm transition-colors {{ $sortBy === 'name' ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                                <span>Name</span>
                                @if($sortBy === 'name')
                                    <svg class="w-4 h-4 transform {{ $sortDirection === 'desc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            </button>
                        </div>
                    </div>

                    <!-- Clear Filters -->
                    @if($search || $categoryId)
                        <button wire:click="clearFilters"
                                class="w-full px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                            Clear Filters
                        </button>
                    @endif
                </div>
            </div>

            <!-- Products Grid -->
            <div class="flex-1">
                <!-- Results Count -->
                <div class="flex items-center justify-between mb-6">
                    <p class="text-sm text-gray-600">
                        Showing <span class="font-medium">{{ $products->firstItem() ?? 0 }}</span> -
                        <span class="font-medium">{{ $products->lastItem() ?? 0 }}</span> of
                        <span class="font-medium">{{ $products->total() }}</span> products
                    </p>
                </div>

                @if($products->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                        @foreach($products as $product)
                            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                                <a href="{{ route('products.show', $product->slug) }}" wire:navigate class="block">
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

                                        @if($product->stock <= 0)
                                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                                <span class="px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-lg">Sold Out</span>
                                            </div>
                                        @elseif($product->stock <= 5)
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
                                    <a href="{{ route('products.show', $product->slug) }}" wire:navigate
                                       class="block font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2 min-h-[2.5rem]">
                                        {{ $product->name }}
                                    </a>
                                    <p class="mt-2 text-sm text-gray-500 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                                    <div class="mt-3 flex items-center justify-between">
                                        <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                        @if($product->stock > 0)
                                            <span class="text-xs font-medium text-green-600">{{ $product->stock }} in stock</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('products.show', $product->slug) }}" wire:navigate
                                       class="mt-4 block w-full text-center py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                        <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">No products found</h3>
                        <p class="mt-2 text-gray-500">Try adjusting your search or filter criteria.</p>
                        <button wire:click="clearFilters"
                                class="mt-6 inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                            Clear Filters
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
