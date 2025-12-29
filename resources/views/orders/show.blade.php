<x-layouts.app>
    <div class="min-h-screen bg-gray-50">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="flex items-center space-x-2 text-sm text-indigo-200 mb-4">
                    <a href="{{ route('user.orders') }}" class="hover:text-white transition-colors">My Orders</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-white">Order Details</span>
                </nav>
                <h1 class="text-3xl md:text-4xl font-bold text-white">{{ $order->order_number ?? 'Order #' . $order->id }}</h1>
                <p class="mt-2 text-indigo-100">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Order Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Status Card -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900">Order Status</h2>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-4">
                                @php
                                    $statusColors = [
                                        'yellow' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'text-yellow-500'],
                                        'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'text-blue-500'],
                                        'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'icon' => 'text-purple-500'],
                                        'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'text-green-500'],
                                        'red' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'text-red-500'],
                                    ];
                                    $colors = $statusColors[$order->status->color()] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'text-gray-500'];
                                @endphp
                                <div class="w-14 h-14 {{ $colors['bg'] }} rounded-xl flex items-center justify-center">
                                    <svg class="w-7 h-7 {{ $colors['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($order->status->value === 'delivered')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        @elseif($order->status->value === 'cancelled')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        @elseif($order->status->value === 'shipped')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        @endif
                                    </svg>
                                </div>
                                <div>
                                    <span class="px-4 py-2 text-sm font-semibold rounded-lg {{ $colors['bg'] }} {{ $colors['text'] }}">
                                        {{ $order->status->label() }}
                                    </span>
                                    <p class="mt-2 text-sm text-gray-500">Last updated: {{ $order->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                        </div>

                        <!-- Desktop Table -->
                        <div class="hidden sm:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Qty</th>
                                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($order->items as $item)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-4">
                                                    <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                                                        @if($item->product && $item->product->primaryImage)
                                                            <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center">
                                                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $item->product_name ?? $item->product->name ?? 'Product' }}</p>
                                                        @if($item->product)
                                                            <a href="{{ route('products.show', $item->product->slug) }}" class="text-sm text-indigo-600 hover:text-indigo-700">View Product</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ${{ number_format($item->price, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                                                ${{ number_format($item->price * $item->quantity, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="sm:hidden divide-y">
                            @foreach($order->items as $item)
                                <div class="p-6">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                                            @if($item->product && $item->product->primaryImage)
                                                <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">{{ $item->product_name ?? $item->product->name ?? 'Product' }}</p>
                                            <p class="text-sm text-gray-500 mt-1">${{ number_format($item->price, 2) }} x {{ $item->quantity }}</p>
                                            <p class="font-semibold text-gray-900 mt-2">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Totals -->
                        <div class="p-6 bg-gray-50 border-t">
                            <div class="space-y-3 max-w-xs ml-auto">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium text-gray-900">${{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-medium {{ $order->shipping_cost > 0 ? 'text-gray-900' : 'text-green-600' }}">
                                        @if($order->shipping_cost > 0)
                                            ${{ number_format($order->shipping_cost, 2) }}
                                        @else
                                            Free
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between text-lg font-bold border-t pt-3 mt-3">
                                    <span class="text-gray-900">Total</span>
                                    <span class="text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    @if($order->notes)
                        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b bg-gray-50">
                                <h2 class="text-lg font-semibold text-gray-900">Order Notes</h2>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-600 bg-gray-50 p-4 rounded-xl">{{ $order->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Payment Information -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Payment Information
                            </h2>
                        </div>
                        <div class="p-6">
                            @if($order->payment)
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Method</span>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ ucwords(str_replace('_', ' ', $order->payment->payment_method)) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Status</span>
                                        @php
                                            $paymentStatusColors = [
                                                'yellow' => 'bg-yellow-100 text-yellow-800',
                                                'blue' => 'bg-blue-100 text-blue-800',
                                                'green' => 'bg-green-100 text-green-800',
                                                'red' => 'bg-red-100 text-red-800',
                                                'orange' => 'bg-orange-100 text-orange-800',
                                            ];
                                            $paymentColorClass = $paymentStatusColors[$order->payment->status->color()] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $paymentColorClass }}">
                                            {{ $order->payment->status->label() }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Amount</span>
                                        <span class="text-sm font-semibold text-gray-900">${{ number_format($order->payment->amount, 2) }}</span>
                                    </div>
                                    @if($order->payment->transaction_id)
                                        <div class="pt-3 border-t">
                                            <span class="text-xs text-gray-500">Transaction ID</span>
                                            <p class="text-sm font-mono text-gray-900 mt-1">{{ $order->payment->transaction_id }}</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <p class="text-sm text-gray-500">No payment information available.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Cancel Order -->
                    @if($order->canBeCancelled())
                        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border-2 border-red-100">
                            <div class="px-6 py-4 border-b bg-red-50">
                                <h2 class="text-lg font-semibold text-red-900">Cancel Order</h2>
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-gray-600 mb-4">
                                    You can cancel this order while it's still being processed. This action cannot be undone.
                                </p>
                                <form action="{{ route('order.cancel', $order->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to cancel this order? This action cannot be undone.')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                            class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Cancel Order
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('user.orders') }}"
                               class="flex items-center justify-center gap-2 w-full py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                View All Orders
                            </a>
                            <a href="{{ route('products.index') }}"
                               class="flex items-center justify-center gap-2 w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                Continue Shopping
                            </a>
                        </div>
                    </div>

                    <!-- Need Help? -->
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-100">
                        <h3 class="font-semibold text-gray-900 mb-2">Need Help?</h3>
                        <p class="text-sm text-gray-600 mb-4">Our customer support team is here to assist you with any questions about your order.</p>
                        <a href="#" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-700">
                            Contact Support
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
