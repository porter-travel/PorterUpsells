<x-app-layout>
    <script
        src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"></script>

    <style>
        .temporary-complete {
            opacity: 0.6;
        }

        .temporary-complete .fulfilment-order-title {
            text-decoration: line-through;
        }
    </style>

    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div class="space-y-2">
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500">Fulfilment checklist</p>
                <h1 class="text-3xl font-semibold text-slate-900 sm:text-4xl">Today's orders</h1>
                <p class="text-sm text-slate-500">Tick orders off as soon as they are prepared or delivered.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white/80 px-4 py-2 text-sm font-medium text-slate-500 shadow-sm">
                {{ now()->timezone(config('app.timezone'))->format('l j F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto flex w-full max-w-5xl flex-col gap-6 px-4 sm:px-6 lg:px-8">
            @if(!empty($fulfilmentKeyDetails))
                <div class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="space-y-1">
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Fulfilment team</p>
                            <h2 class="text-2xl font-semibold text-slate-900">{{ $fulfilmentKeyDetails['name'] }}</h2>
                            <p class="text-sm text-slate-500">Orders scheduled for today are listed below.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-xs text-slate-500">
                            Share this page with your on-the-ground team so everyone stays in sync.
                        </div>
                    </div>
                </div>
            @endif

            @php
                $hotelCount = count($hotels);
            @endphp

            @if($hotelCount > 1)
                <div class="rounded-3xl border border-slate-200 bg-white/95 p-5 shadow-sm">
                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Properties</p>
                        <div id="hotelTabs" class="flex flex-wrap gap-2">
                            @foreach($hotels as $index => $hotel)
                                @php
                                    $readyCount = isset($hotel['orders']['ready']) ? count($hotel['orders']['ready']) : 0;
                                @endphp
                                <button
                                    type="button"
                                    class="hotel-tab inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-indigo-200 hover:text-indigo-600"
                                    data-hotel-target="ordersPanel{{ $index }}"
                                    @if($index === 0) aria-pressed="true" @else aria-pressed="false" @endif
                                >
                                    <span>{{ $hotel['name'] }}</span>
                                    @if($readyCount > 0)
                                        <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700">{{ $readyCount }}</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @foreach($hotels as $index => $hotel)
                @php
                    $ready = $hotel['orders']['ready'] ?? [];
                    $pending = $hotel['orders']['pending'] ?? [];
                    $complete = $hotel['orders']['complete'] ?? [];
                    $hasOrders = count($ready) > 0 || count($pending) > 0 || count($complete) > 0;
                @endphp

                <section
                    id="ordersPanel{{ $index }}"
                    data-hotel-panel
                    class="orders-panel space-y-6 @if($index > 0 && $hotelCount > 1) hidden @endif"
                >
                    <div class="flex items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 shadow-sm">
                        @if(!empty($hotel['logo']))
                            <img src="{{ $hotel['logo'] }}" alt="{{ $hotel['name'] }} logo" class="h-12 w-12 rounded-xl object-cover" loading="lazy">
                        @endif
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Property</p>
                            <h2 class="text-xl font-semibold text-slate-900">{{ $hotel['name'] }}</h2>
                        </div>
                    </div>

                    @if($hasOrders)
                        @php
                            $sections = [
                                'ready' => [
                                    'title' => 'Ready to fulfil',
                                    'description' => 'Everything that can be handed over right now.',
                                    'orders' => $ready,
                                ],
                                'pending' => [
                                    'title' => 'Awaiting arrival',
                                    'description' => 'These unlock once the guest has checked in.',
                                    'orders' => $pending,
                                ],
                                'complete' => [
                                    'title' => 'Completed today',
                                    'description' => 'A record of everything already delivered.',
                                    'orders' => $complete,
                                ],
                            ];
                        @endphp

                        @foreach($sections as $section => $meta)
                            @if(count($meta['orders']) > 0)
                                <div class="space-y-4">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <h3 class="text-base font-semibold text-slate-900">{{ $meta['title'] }}</h3>
                                            <p class="text-xs text-slate-500">{{ $meta['description'] }}</p>
                                        </div>
                                        <span class="inline-flex items-center justify-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">{{ count($meta['orders']) }}</span>
                                    </div>
                                    <div class="space-y-4">
                                        @foreach($meta['orders'] as $order)
                                            @include('admin.partials.fulfilment-panel', ['order' => $order, 'status' => $section, 'key' => $key, 'integration' => $hotel['integration']])
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="flex flex-col items-start gap-3 rounded-3xl border border-dashed border-slate-300 bg-white/80 p-6 text-sm text-slate-500 shadow-sm">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 7H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M6 11H18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M9 15H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-700">No orders for this property today</p>
                                <p class="mt-1 text-xs text-slate-500">As new orders arrive they will appear here automatically.</p>
                            </div>
                        </div>
                    @endif
                </section>
            @endforeach
        </div>
    </div>

    <script>
        $(document).ready(function () {
            const activateTab = (targetId) => {
                const panels = $('[data-hotel-panel]');
                const tabs = $('.hotel-tab');

                panels.addClass('hidden');
                $(`#${targetId}`).removeClass('hidden');

                tabs.each(function () {
                    $(this)
                        .removeClass('bg-indigo-50 border-indigo-300 text-indigo-600')
                        .attr('aria-pressed', 'false');
                });

                const activeTab = $(`.hotel-tab[data-hotel-target="${targetId}"]`);
                activeTab
                    .addClass('bg-indigo-50 border-indigo-300 text-indigo-600')
                    .attr('aria-pressed', 'true');
            };

            $('.hotel-tab').on('click', function () {
                const target = $(this).data('hotel-target');
                activateTab(target);
            });

            const firstTab = $('.hotel-tab').first();
            if (firstTab.length) {
                activateTab(firstTab.data('hotel-target'));
            }

            $('[data-action="fulfilOrder"]').on('change', function () {
                const orderId = $(this).attr('id').replace('order', '');
                const key = $(this).data('key');
                const status = $(this).is(':checked') ? 'complete' : 'pending';

                const that = $(this);
                $.ajax({
                    url: `/fulfil-order/`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        orderId: orderId,
                        status: status,
                        key: key
                    },
                    success: function () {
                        that.parents('.fulfilment-panel').addClass('temporary-complete');
                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            });
        });

    </script>
</x-app-layout>
