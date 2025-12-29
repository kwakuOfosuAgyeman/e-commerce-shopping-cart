<x-layouts.app>
    <div class="min-h-screen bg-gray-50">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl md:text-4xl font-bold text-white">My Orders</h1>
                <p class="mt-2 text-indigo-100">Track and manage your orders</p>
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

            @if($orders->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">Order History</h2>
                            <span class="text-sm text-gray-500">{{ $orders->total() }} {{ Str::plural('order', $orders->total()) }}</span>
                        </div>
                    </div>

                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Items</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($orders as $order)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-semibold text-indigo-600">{{ $order->order_number ?? '#' . $order->id }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</span>
                                            <span class="block text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-900">{{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'yellow' => 'bg-yellow-100 text-yellow-800',
                                                    'blue' => 'bg-blue-100 text-blue-800',
                                                    'purple' => 'bg-purple-100 text-purple-800',
                                                    'green' => 'bg-green-100 text-green-800',
                                                    'red' => 'bg-red-100 text-red-800',
                                                ];
                                                $colorClass = $statusColors[$order->status->color()] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                                {{ $order->status->label() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <a href="{{ route('order.track', $order->id) }}"
                                               class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-700">
                                                View Details
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="md:hidden divide-y">
                        @foreach($orders as $order)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <p class="font-semibold text-indigo-600">{{ $order->order_number ?? '#' . $order->id }}</p>
                                        <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                    @php
                                        $statusColors = [
                                            'yellow' => 'bg-yellow-100 text-yellow-800',
                                            'blue' => 'bg-blue-100 text-blue-800',
                                            'purple' => 'bg-purple-100 text-purple-800',
                                            'green' => 'bg-green-100 text-green-800',
                                            'red' => 'bg-red-100 text-red-800',
                                        ];
                                        $colorClass = $statusColors[$order->status->color()] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $colorClass }}">
                                        {{ $order->status->label() }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm text-gray-600">{{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</p>
                                        <p class="font-semibold text-gray-900 text-lg">${{ number_format($order->total_amount, 2) }}</p>
                                    </div>
                                    <a href="{{ route('order.track', $order->id) }}"
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t">
                        {{ $orders->links() }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center max-w-2xl mx-auto">
                    <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">No orders yet</h3>
                    <p class="mt-2 text-gray-500 max-w-md mx-auto">You haven't placed any orders yet. Start shopping to see your order history here.</p>
                    <a href="{{ route('products.index') }}"
                       class="mt-6 inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
