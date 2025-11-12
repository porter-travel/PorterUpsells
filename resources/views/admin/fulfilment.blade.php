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

        summary::-webkit-details-marker {
            display: none;
        }

        summary:focus-visible {
            outline: none;
        }

        .collapsible-chevron {
            transition: transform 0.2s ease;
        }

        details[open] .collapsible-chevron {
            transform: rotate(180deg);

        }
    </style>

    <div class="py-6">

        <div class="mx-auto w-full max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            @php
                $baseRoute = route('fulfilment', ['key' => $key]);
                $prevUrl = $baseRoute . '?date=' . $previousDate->format('Y-m-d');
                $nextUrl = $baseRoute . '?date=' . $nextDate->format('Y-m-d');
                $todayUrl = $baseRoute;
                if (!$currentDate->isSameDay($todayDate)) {
                    $todayUrl .= '?date=' . $todayDate->format('Y-m-d');
                }
            @endphp

            <div class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Schedule</p>
                        <p class="text-lg font-semibold text-slate-900">{{ $currentDateFormatted }}</p>
                        <p class="text-sm text-slate-500">Jump backwards or forwards to review other service days.</p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ $prevUrl }}"
                               class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 transition hover:border-indigo-300 hover:text-indigo-600 sm:text-sm">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.5 4.5L7.5 10L12.5 15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Prev day
                            </a>
                            @if(!$currentDate->isSameDay($todayDate))
                                <a href="{{ $todayUrl }}"
                                   class="inline-flex items-center gap-1 rounded-full border border-indigo-200 bg-indigo-50 px-4 py-2 text-xs font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100 sm:text-sm">
                                    Today
                                </a>
                            @endif
                            <a href="{{ $nextUrl }}"
                               class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 transition hover:border-indigo-300 hover:text-indigo-600 sm:text-sm">
                                Next day
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.5 4.5L12.5 10L7.5 15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>
                        <form method="GET" action="{{ $baseRoute }}" class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 shadow-sm">
                            <label for="date-picker" class="text-xs font-semibold uppercase tracking-widest text-slate-500">Go to</label>
                            <input id="date-picker" type="date" name="date" value="{{ $currentDate->format('Y-m-d') }}" class="w-36 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-slate-700 focus:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-200" onchange="this.form.submit()" />
                        </form>
                    </div>
                </div>

                @php
                    $hotelCount = count($hotels);
                @endphp

                @if($hotelCount > 1)
                    <div class="mt-6 space-y-3 border-t border-slate-200 pt-6">

                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Properties</p>
                        <div id="hotelTabs" class="flex flex-wrap gap-2">
                            @foreach($hotels as $index => $hotel)
                                @php
                                    $readyCount = isset($hotel['orders']['ready']) ? count($hotel['orders']['ready']) : 0;

                                    $pendingCount = isset($hotel['orders']['pending']) ? count($hotel['orders']['pending']) : 0;
                                    $completeCount = isset($hotel['orders']['complete']) ? count($hotel['orders']['complete']) : 0;
                                    $totalCount = $readyCount + $pendingCount + $completeCount;

                                @endphp
                                <button
                                    type="button"
                                    class="hotel-tab inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-indigo-200 hover:text-indigo-600"
                                    data-hotel-target="ordersPanel{{ $index }}"
                                    @if($index === 0) aria-pressed="true" @else aria-pressed="false" @endif
                                >
                                    <span>{{ $hotel['name'] }}</span>

                                    @if($totalCount > 0)
                                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">{{ $totalCount }}</span>

                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                @endif
            </div>


            @foreach($hotels as $index => $hotel)
                @php
                    $ready = $hotel['orders']['ready'] ?? [];
                    $pending = $hotel['orders']['pending'] ?? [];
                    $complete = $hotel['orders']['complete'] ?? [];
                    $hasOrders = count($ready) > 0 || count($pending) > 0 || count($complete) > 0;

                    $statusSections = [
                        'ready' => [
                            'title' => 'Ready to fulfil',
                            'orders' => $ready,
                        ],
                        'pending' => [
                            'title' => 'Awaiting arrival',
                            'orders' => $pending,
                        ],
                        'complete' => [
                            'title' => 'Completed today',
                            'orders' => $complete,
                        ],
                    ];
                    $statusDots = [
                        'ready' => 'bg-emerald-500',
                        'pending' => 'bg-amber-500',
                        'complete' => 'bg-slate-400',
                    ];
                @endphp

                <section
                    id="ordersPanel{{ $index }}"
                    data-hotel-panel
                    class="orders-panel space-y-6 rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm @if($index > 0 && $hotelCount > 1) hidden @endif"
                >
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex flex-1 items-center gap-4">
                            @if(!empty($hotel['logo']))
                                <img src="{{ $hotel['logo'] }}" alt="{{ $hotel['name'] }} logo" class="h-14 w-14 flex-shrink-0 rounded-xl object-cover" loading="lazy">
                            @endif
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Property</p>
                                <h2 class="text-xl font-semibold text-slate-900">{{ $hotel['name'] }}</h2>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs text-slate-500 sm:text-sm">
                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600">
                                {{ count($ready) }} ready
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600">
                                {{ count($pending) }} waiting
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 font-medium text-slate-600">
                                {{ count($complete) }} complete
                            </span>
                        </div>
                    </div>

                    @if($hasOrders)
                        <div class="space-y-4">
                            @foreach($statusSections as $section => $meta)
                                @if(count($meta['orders']) > 0)
                                    <details class="group overflow-hidden rounded-2xl border border-slate-200 bg-white/90 shadow-sm" @if($section !== 'complete') open @endif>
                                        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-4 py-3 text-sm font-semibold text-slate-700">
                                            <span class="flex items-center gap-2">
                                                <span class="h-2.5 w-2.5 rounded-full {{ $statusDots[$section] ?? 'bg-slate-400' }}"></span>
                                                {{ $meta['title'] }}
                                            </span>
                                            <span class="flex items-center gap-2">
                                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">{{ count($meta['orders']) }}</span>
                                                <svg class="collapsible-chevron h-4 w-4 text-slate-400" viewBox="0 0 20 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1.5 1.5L10 10L18.5 1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </summary>
                                        <div class="border-t border-slate-200 bg-white">
                                            <div class="space-y-4 p-4">
                                                @foreach($meta['orders'] as $order)
                                                    @include('admin.partials.fulfilment-panel', ['order' => $order, 'status' => $section, 'key' => $key, 'integration' => $hotel['integration']])
                                                @endforeach
                                            </div>
                                        </div>
                                    </details>
                                @endif
                            @endforeach
                        </div>
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
                                <p class="font-semibold text-slate-700">No orders for this property on this day</p>
                                <p class="mt-1 text-xs text-slate-500">When new orders are scheduled they will automatically appear here.</p>
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

                        const panel = that.parents('.fulfilment-panel');
                        if (status === 'complete') {
                            panel.addClass('temporary-complete');
                        } else {
                            panel.removeClass('temporary-complete');
                        }

                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            });
        });

    </script>
</x-app-layout>
