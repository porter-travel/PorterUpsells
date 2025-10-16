@php
    $isComplete = $status === 'complete';
    $isPending = $status === 'pending';
    $titleClasses = 'fulfilment-order-title text-lg font-semibold text-slate-900';

    $statusLabels = [
        'ready' => 'Ready to fulfil',
        'pending' => 'Awaiting arrival',
        'complete' => 'Completed',
    ];
    $statusThemes = [
        'ready' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
        'pending' => 'bg-amber-50 text-amber-700 border border-amber-100',
        'complete' => 'bg-slate-100 text-slate-600 border border-slate-200',
    ];


    if ($isComplete) {
        $titleClasses .= ' text-slate-500 line-through';
    }
@endphp


<div class="fulfilment-panel group rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-sm transition hover:border-indigo-200 hover:shadow-md sm:p-5 @if($isComplete) opacity-70 @endif">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:gap-4">

            <div class="flex flex-shrink-0 items-start pt-1">
                <div class="relative">
                    <input
                        data-action="fulfilOrder"
                        data-key="{{ $key }}"
                        id="order{{ $order['id'] }}"
                        type="checkbox"
                        class="peer sr-only"
                        @if($isComplete) checked @elseif($isPending) disabled @endif
                    >
                    <label for="order{{ $order['id'] }}" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-400 transition peer-checked:border-emerald-400 peer-checked:bg-emerald-50 peer-checked:text-emerald-600 peer-disabled:cursor-not-allowed peer-disabled:opacity-60">
                        <svg class="hidden h-5 w-5 peer-checked:block" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19.6316 0.344169C20.1228 0.803061 20.1228 1.54707 19.6316 2.00596L6.79245 14L0.368419 7.99881C-0.122806 7.53991 -0.122806 6.7959 0.368419 6.33701C0.859645 5.87812 1.65608 5.87812 2.1473 6.33701L6.79245 10.6764L17.8527 0.344169C18.3439 -0.114723 19.1404 -0.114723 19.6316 0.344169Z" fill="currentColor"/>
                        </svg>
                        @if($isPending)
                            <svg class="h-5 w-5 peer-checked:hidden" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M10 6V10.25L12.5 12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        @endif
                    </label>
                </div>
            </div>

            <div class="flex-1 space-y-4">

                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $statusThemes[$status] ?? 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                <span class="inline-block h-1.5 w-1.5 rounded-full bg-current"></span>
                                {{ $statusLabels[$status] ?? 'Order' }}
                            </span>
                            <div class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600">
                                Room {{ $order['room'] ?: '—' }}
                            </div>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Guest</p>
                            <p class="{{ $titleClasses }}">{{ $order['name'] }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 lg:justify-end">
                        @if(!empty($integration))
                            <div class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">

                                <span>{{ $order['checkin'] ? 'Checked in' : 'Not arrived' }}</span>
                            </div>
                        @endif
                        @if(!empty($order['after_checkin']) && empty($order['checkin']))
                            <div class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5"/>
                                    <path d="M10 6V10L12.5 12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>Awaiting check-in</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Order items</p>
                    <ul class="space-y-1 text-sm text-slate-600">
                        @foreach($order['items'] as $item)
                            <li>{{ $item['quantity'] }} × {{ $item['name'] }}</li>
                        @endforeach
                    </ul>
                </div>

                @if($isPending)
                    <p class="text-xs font-medium text-amber-600">This order will unlock once the guest has checked in.</p>
                @endif
            </div>
        </div>
    </div>
</div>
