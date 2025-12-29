<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- User Info Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <!-- Avatar -->
                            <div class="flex justify-center mb-6">
                                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-3xl font-bold text-blue-600">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>

                            <!-- User Details -->
                            <div class="text-center mb-6">
                                <h3 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h3>
                                <p class="text-gray-500">{{ $user->email }}</p>
                                @if($user->phone)
                                    <p class="text-sm text-gray-500 mt-1">{{ $user->phone }}</p>
                                @endif
                            </div>

                            <!-- Member Since -->
                            <div class="border-t pt-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Member since</span>
                                    <span class="text-gray-900 font-medium">{{ $user->created_at->format('M Y') }}</span>
                                </div>
                                @if($user->email_verified_at)
                                    <div class="flex justify-between text-sm mt-2">
                                        <span class="text-gray-500">Email status</span>
                                        <span class="inline-flex items-center text-green-600">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Verified
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Quick Actions -->
                            <div class="mt-6 space-y-3">
                                <a href="{{ route('profile') }}"
                                   class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                    Edit Profile
                                </a>
                                <a href="{{ route('user.orders') }}"
                                   class="block w-full text-center border border-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg hover:bg-gray-50 transition-colors">
                                    View All Orders
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats and Recent Activity -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Total Spent -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500">Total Spent</p>
                                    <p class="text-2xl font-bold text-gray-900">${{ number_format($totalSpent, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Total Orders -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500">Total Orders</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Last Order -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500">Last Order</p>
                                    @if($lastOrder)
                                        <p class="text-lg font-bold text-gray-900">{{ $lastOrder->created_at->diffForHumans() }}</p>
                                    @else
                                        <p class="text-lg font-bold text-gray-400">No orders yet</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                                <a href="{{ route('user.orders') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                    View All &rarr;
                                </a>
                            </div>

                            @if($user->orders->count() > 0)
                                <div class="space-y-4">
                                    @foreach($user->orders->take(5) as $order)
                                        <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                                            <div class="flex items-center gap-4">
                                                <div class="flex-shrink-0">
                                                    @php
                                                        $statusColors = [
                                                            'yellow' => 'bg-yellow-100 text-yellow-600',
                                                            'blue' => 'bg-blue-100 text-blue-600',
                                                            'purple' => 'bg-purple-100 text-purple-600',
                                                            'green' => 'bg-green-100 text-green-600',
                                                            'red' => 'bg-red-100 text-red-600',
                                                        ];
                                                        $colorClass = $statusColors[$order->status->color()] ?? 'bg-gray-100 text-gray-600';
                                                    @endphp
                                                    <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $colorClass }}">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $order->order_number ?? '#' . $order->id }}</p>
                                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                                                @php
                                                    $badgeColors = [
                                                        'yellow' => 'bg-yellow-100 text-yellow-800',
                                                        'blue' => 'bg-blue-100 text-blue-800',
                                                        'purple' => 'bg-purple-100 text-purple-800',
                                                        'green' => 'bg-green-100 text-green-800',
                                                        'red' => 'bg-red-100 text-red-800',
                                                    ];
                                                    $badgeClass = $badgeColors[$order->status->color()] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $badgeClass }}">
                                                    {{ $order->status->label() }}
                                                </span>
                                            </div>
                                            <a href="{{ route('order.track', $order->id) }}"
                                               class="ml-4 text-blue-600 hover:text-blue-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <!-- Empty State -->
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <h4 class="mt-4 text-lg font-medium text-gray-900">No orders yet</h4>
                                    <p class="mt-2 text-sm text-gray-500">Start shopping to see your order history here.</p>
                                    <a href="{{ route('products.index') }}"
                                       class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        Browse Products
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <a href="{{ route('products.index') }}"
                                   class="flex flex-col items-center p-4 rounded-lg border hover:bg-gray-50 transition-colors">
                                    <svg class="w-8 h-8 text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700">Products</span>
                                </a>
                                <a href="{{ route('cart') }}"
                                   class="flex flex-col items-center p-4 rounded-lg border hover:bg-gray-50 transition-colors">
                                    <svg class="w-8 h-8 text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700">Cart</span>
                                </a>
                                <a href="{{ route('user.orders') }}"
                                   class="flex flex-col items-center p-4 rounded-lg border hover:bg-gray-50 transition-colors">
                                    <svg class="w-8 h-8 text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700">Orders</span>
                                </a>
                                <a href="{{ route('profile') }}"
                                   class="flex flex-col items-center p-4 rounded-lg border hover:bg-gray-50 transition-colors">
                                    <svg class="w-8 h-8 text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700">Settings</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
