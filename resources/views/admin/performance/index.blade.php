@php
    $queryString = request()->getQueryString();
    $queryString = $queryString ? '?' . $queryString : '';

    $metrics = [
        [
            'label' => 'Dashboard views',
            'value' => number_format($totalDashboardViews),
            'icon' => 'layout-dashboard',
            'description' => 'Guest visits to your digital concierge.',
        ],
        [
            'label' => 'Product views',
            'value' => number_format($totalProductViews),
            'icon' => 'sparkles',
            'description' => 'How often experiences were explored.',
        ],
        [
            'label' => 'Orders',
            'value' => number_format($totalOrders),
            'icon' => 'shopping-bag',
            'description' => 'Confirmed purchases across the period.',
        ],
        [
            'label' => 'Adds to cart',
            'value' => number_format($totalAddsToCart),
            'icon' => 'shopping-cart',
            'description' => 'Moments guests signalled intent to buy.',
        ],
        [
            'label' => 'Emails sent',
            'value' => number_format($emailCount),
            'icon' => 'mail',
            'description' => 'Automated campaigns delivered to guests.',
        ],
        [
            'label' => 'Total revenue',
            'value' => '£' . \App\Helpers\Money::format($totalSales),
            'icon' => 'coins',
            'description' => 'Subtotal generated from guest orders.',
        ],
    ];
@endphp

<x-hotel-admin-layout :hotel="$hotel">
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-indigo-500">Performance</p>
                <h1 class="mt-3 text-3xl font-semibold text-slate-900 sm:text-4xl">
                    {{ $hotel ? $hotel->name . ' performance' : 'Portfolio performance overview' }}
                </h1>
                <p class="mt-4 max-w-2xl text-sm text-slate-500">
                    Track how guests are interacting with your experiences across booking journeys, carts and automated emails.
                </p>
            </div>
            <div class="flex w-full flex-col items-stretch gap-4 sm:flex-row sm:items-center sm:justify-end">
                <div
                    x-data="{ open: false }"
                    class="relative"
                >
                    <button
                        type="button"
                        class="flex w-full items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-white sm:min-w-[240px]"
                        @click="open = !open"
                        @click.outside="open = false"
                    >
                        <span>{{ $hotel ? $hotel->name : 'All properties' }}</span>
                        <i data-lucide="chevron-down" class="h-4 w-4"></i>
                    </button>
                    <div
                        x-cloak
                        x-show="open"
                        x-transition.scale.origin.top
                        class="absolute right-0 z-20 mt-2 w-64 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl"
                    >
                        <div class="divide-y divide-slate-100">
                            <a
                                href="{{ route('performance.index') . $queryString }}"
                                class="flex items-center justify-between px-4 py-3 text-sm font-medium text-slate-600 transition hover:bg-indigo-50 hover:text-indigo-600"
                            >
                                <span>All properties</span>
                                @if(!$hotel)
                                    <i data-lucide="check" class="h-4 w-4 text-indigo-500"></i>
                                @endif
                            </a>
                            @foreach($hotels as $loopHotel)
                                <a
                                    href="{{ route('performance.index', ['hotel_id' => $loopHotel->id]) . $queryString }}"
                                    class="flex items-center justify-between px-4 py-3 text-sm font-medium text-slate-600 transition hover:bg-indigo-50 hover:text-indigo-600"
                                >
                                    <span>{{ $loopHotel->name }}</span>
                                    @if($hotel && $hotel->id === $loopHotel->id)
                                        <i data-lucide="check" class="h-4 w-4 text-indigo-500"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10">
        <section class="rounded-3xl border border-white/10 bg-white/80 p-6 shadow-lg shadow-indigo-200/20 backdrop-blur">
            <x-date-filter-bar :startDate="$startDate" :endDate="$endDate" class="gap-6" />
        </section>

        <section class="space-y-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">Key engagement metrics</h2>
                    <p class="mt-2 text-sm text-slate-500">Understand how guests discover and convert across your Enhance My Stay journey.</p>
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($metrics as $metric)
                    <article class="relative overflow-hidden rounded-3xl border border-white/60 bg-gradient-to-br from-white to-indigo-50/80 p-6 shadow-lg shadow-indigo-200/30">
                        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-indigo-500/10 blur-2xl"></div>
                        <div class="relative flex items-center justify-between">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600">
                                <i data-lucide="{{ $metric['icon'] }}" class="h-5 w-5"></i>
                            </span>
                            <span class="text-xs font-semibold uppercase tracking-widest text-indigo-400">{{ strtoupper($metric['label']) }}</span>
                        </div>
                        <p class="relative mt-6 text-4xl font-semibold text-slate-900">{{ $metric['value'] }}</p>
                        <p class="relative mt-3 text-sm text-slate-500">{{ $metric['description'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        @if(!$hotel && count($hotelOrders) > 0)
            <section class="space-y-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-slate-900">Property performance</h2>
                        <p class="text-sm text-slate-500">Compare how each property performs across orders and revenue.</p>
                    </div>
                </div>
                <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/80 shadow-lg shadow-indigo-200/20">
                    <div class="overflow-x-auto">
                        <table id="hotelOrdersTableBody" class="min-w-full divide-y divide-white/40 text-left text-sm text-slate-600">
                            <thead class="bg-indigo-50/60 text-xs font-semibold uppercase tracking-widest text-indigo-600">
                            <tr>
                                <th scope="col" class="px-6 py-3" data-sort="hotel_name">
                                    <div class="flex items-center gap-2">
                                        Property
                                        <span class="sort-arrow text-indigo-400"></span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-right" data-sort="total_orders">
                                    <div class="flex items-center justify-end gap-2">
                                        Total orders
                                        <span class="sort-arrow text-indigo-400"></span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-right" data-sort="total_value">
                                    <div class="flex items-center justify-end gap-2">
                                        Total value
                                        <span class="sort-arrow text-indigo-400"></span>
                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-white/40">
                            @foreach($hotelOrders as $hotelOrder)
                                <tr class="transition hover:bg-indigo-50/60">
                                    <td class="px-6 py-4 font-medium text-slate-700" data-key="hotel_name">{{ $hotelOrder['hotel_name'] }}</td>
                                    <td class="px-6 py-4 text-right text-slate-700" data-key="total_orders">{{ $hotelOrder['total_orders'] }}</td>
                                    <td class="px-6 py-4 text-right text-slate-700" data-key="total_value">£{{ \App\Helpers\Money::format($hotelOrder['total_value']) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif

        @if(count($productAnalytics) > 0)
            <section class="space-y-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-slate-900">Product performance</h2>
                        <p class="text-sm text-slate-500">Review which experiences and add-ons convert best.</p>
                    </div>
                </div>
                <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/80 shadow-lg shadow-indigo-200/20">
                    <div class="overflow-x-auto">
                        <table id="productOrdersTableBody" class="min-w-full divide-y divide-white/40 text-left text-sm text-slate-600">
                            <thead class="bg-indigo-50/60 text-xs font-semibold uppercase tracking-widest text-indigo-600">
                            <tr>
                                <th scope="col" class="px-6 py-3" data-sort="hotel_name">
                                    <div class="flex items-center gap-2">
                                        Product
                                        <span class="sort-arrow text-indigo-400"></span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-right" data-sort="total_orders">
                                    <div class="flex items-center justify-end gap-2">
                                        Total orders
                                        <span class="sort-arrow text-indigo-400"></span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-right" data-sort="total_value">
                                    <div class="flex items-center justify-end gap-2">
                                        Total value
                                        <span class="sort-arrow text-indigo-400"></span>
                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-white/40">
                            @foreach($productAnalytics as $product)
                                <tr class="transition hover:bg-indigo-50/60">
                                    <td class="px-6 py-4 font-medium text-slate-700" data-key="hotel_name">{{ $product['product_name'] }}</td>
                                    <td class="px-6 py-4 text-right text-slate-700" data-key="total_orders">{{ $product['quantity'] }}</td>
                                    <td class="px-6 py-4 text-right text-slate-700" data-key="total_value">£{{ $product['total_value'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif

        @if(count($customerAnalytics) > 0)
            <section class="space-y-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-slate-900">Customer performance</h2>
                        <p class="text-sm text-slate-500">Identify guests who engage most with your upsell programme.</p>
                    </div>
                </div>
                <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/80 shadow-lg shadow-indigo-200/20">
                    <div class="overflow-x-auto">
                        <table id="customerOrdersTableBody" class="min-w-full divide-y divide-white/40 text-left text-sm text-slate-600">
                            <thead class="bg-indigo-50/60 text-xs font-semibold uppercase tracking-widest text-indigo-600">
                            <tr>
                                <th scope="col" class="px-6 py-3" data-sort="hotel_name">
                                    <div class="flex items-center gap-2">
                                        Customer
                                        <span class="sort-arrow text-indigo-400"></span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-right" data-sort="total_orders">
                                    <div class="flex items-center justify-end gap-2">
                                        Total orders
                                        <span class="sort-arrow text-indigo-400"></span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-right" data-sort="total_value">
                                    <div class="flex items-center justify-end gap-2">
                                        Total value
                                        <span class="sort-arrow text-indigo-400"></span>
                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-white/40">
                            @foreach($customerAnalytics as $customer)
                                <tr class="transition hover:bg-indigo-50/60">
                                    <td class="px-6 py-4 font-medium text-slate-700" data-key="hotel_name">{{ $customer->email }}</td>
                                    <td class="px-6 py-4 text-right text-slate-700" data-key="total_orders">{{ $customer->total_orders }}</td>
                                    <td class="px-6 py-4 text-right text-slate-700" data-key="total_value">£{{ $customer->total_value }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sortTable = (tableBody, headers) => {
                headers.forEach(header => {
                    header.style.cursor = 'pointer';
                    header.addEventListener('click', () => {
                        const sortKey = header.getAttribute('data-sort');
                        const rows = Array.from(tableBody.querySelectorAll('tr'));
                        const isDescending = header.classList.toggle('descending');

                        headers.forEach(h => {
                            h.classList.remove('active');
                            const arrow = h.querySelector('.sort-arrow');
                            if (arrow) {
                                arrow.textContent = '';
                            }
                        });

                        header.classList.add('active');
                        const arrow = header.querySelector('.sort-arrow');
                        if (arrow) {
                            arrow.textContent = isDescending ? '↓' : '↑';
                        }

                        rows.sort((a, b) => {
                            const cellA = a.querySelector(`[data-key="${sortKey}"]`)?.textContent.trim() || '';
                            const cellB = b.querySelector(`[data-key="${sortKey}"]`)?.textContent.trim() || '';

                            if (sortKey === 'total_orders' || sortKey === 'total_value') {
                                const valueA = parseFloat(cellA.replace(/[^0-9.-]+/g, '')) || 0;
                                const valueB = parseFloat(cellB.replace(/[^0-9.-]+/g, '')) || 0;
                                return isDescending ? valueB - valueA : valueA - valueB;
                            }

                            return isDescending ? cellB.localeCompare(cellA) : cellA.localeCompare(cellB);
                        });

                        rows.forEach(row => tableBody.appendChild(row));
                    });
                });
            };

            const initializeTableSorting = (tableSelector, defaultSortKey) => {
                const table = document.querySelector(tableSelector);
                if (!table) {
                    return;
                }

                const tableBody = table.querySelector('tbody');
                const headers = table.querySelectorAll(`thead th[data-sort]`);
                sortTable(tableBody, headers);

                const defaultHeader = table.querySelector(`th[data-sort="${defaultSortKey}"]`);
                if (defaultHeader) {
                    defaultHeader.classList.add('descending');
                    const arrow = defaultHeader.querySelector('.sort-arrow');
                    if (arrow) {
                        arrow.textContent = '↓';
                    }
                }
            };

            initializeTableSorting('#hotelOrdersTableBody', 'total_value');
            initializeTableSorting('#productOrdersTableBody', 'total_value');
            initializeTableSorting('#customerOrdersTableBody', 'total_value');
        });
    </script>
</x-hotel-admin-layout>
