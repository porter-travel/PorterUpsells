@if(!isset($product))
    @php
        $product = new \App\Models\Product();
    @endphp
@endif

@php
    $on_arrival_checked = '';
    if(isset($product->specifics['on_arrival'])){
      if($product->specifics['on_arrival']){
         $on_arrival_checked = 'checked';
      }
    }else{
        $on_arrival_checked = 'checked';
    }
@endphp

<div class="grid gap-6 lg:grid-cols-[minmax(0,220px)_1fr]">
    <div class="rounded-2xl border border-white/60 bg-white/30 p-4 shadow-inner shadow-white/40 backdrop-blur">
        <p class="mb-4 text-xs font-semibold uppercase tracking-widest text-slate-500">Configuration</p>
        <div class="space-y-2">
            <button type="button" data-settings-button data-target="availability-tab" class="settings-button flex w-full items-center justify-between rounded-xl border border-transparent bg-white/20 px-4 py-2 text-left text-sm font-semibold text-slate-600 transition">
                <span>Availability</span>
                <i data-lucide="calendar" class="h-4 w-4"></i>
            </button>
            @if($hotel->property_type == 'hotel' && $type == 'standard')
                <button type="button" data-settings-button data-target="storage-tab" class="settings-button flex w-full items-center justify-between rounded-xl border border-transparent bg-white/20 px-4 py-2 text-left text-sm font-semibold text-slate-600 transition">
                    <span>Storage / Quality</span>
                    <i data-lucide="package-check" class="h-4 w-4"></i>
                </button>
            @endif
            <button type="button" data-settings-button data-target="notice-tab" class="settings-button flex w-full items-center justify-between rounded-xl border border-transparent bg-white/20 px-4 py-2 text-left text-sm font-semibold text-slate-600 transition">
                <span>Notice period</span>
                <i data-lucide="alarm-clock" class="h-4 w-4"></i>
            </button>
            @if($type == 'restaurant')
                @foreach($hotel->connections as $connection)
                    @if($connection->key == 'resdiary_microsite_name')
                        <button type="button" data-settings-button data-target="resdiary-tab" class="settings-button flex w-full items-center justify-between rounded-xl border border-transparent bg-white/20 px-4 py-2 text-left text-sm font-semibold text-slate-600 transition">
                            <span>ResDiary</span>
                            <i data-lucide="utensils-crossed" class="h-4 w-4"></i>
                        </button>
                    @endif
                @endforeach
            @endif
            @if($method == 'update')
                <button type="button" data-settings-button data-target="unavailability-tab" class="settings-button flex w-full items-center justify-between rounded-xl border border-transparent bg-white/20 px-4 py-2 text-left text-sm font-semibold text-slate-600 transition">
                    <span>Unavailability</span>
                    <i data-lucide="clock-3" class="h-4 w-4"></i>
                </button>
            @endif
        </div>
    </div>

    <div class="space-y-6">
        <div class="settings-tab rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-sm" data-settings-tab id="availability-tab">
            <div class="grid gap-6 lg:grid-cols-2">
                @include('admin.product.partials/availability-pickers')
                @include('admin.product.partials/available-days')
            </div>
        </div>

        @if($hotel->property_type == 'hotel' && $type == 'standard')
            <div class="settings-tab hidden rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-sm" data-settings-tab id="storage-tab">
                <h4 class="text-lg font-semibold text-slate-900">Storage / Quality</h4>
                <div class="mt-4 space-y-3">
    <label class="group flex items-start gap-3 rounded-2xl border border-slate-200 bg-white/80 px-5 py-4 text-sm font-medium text-slate-700 shadow-sm transition hover:border-indigo-200 hover:bg-slate-50 cursor-pointer">
        <input type="hidden" name="specifics[after_checkin]" value="0">
        <input
            type="checkbox"
            name="specifics[after_checkin]"
            value="1"
            id="after_checkin"
            class="peer sr-only"
            @if(($product->specifics['after_checkin'] ?? false))
                checked
            @endif
        >
        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-[4px] border border-slate-300 bg-white text-transparent transition-colors duration-150 ease-in-out
            peer-checked:border-indigo-500 peer-checked:bg-indigo-500 peer-checked:text-white">
            ✓
        </span>
        <div class="flex flex-col flex-1">
            <span class="font-semibold text-slate-800 group-hover:text-slate-900">
                Product can only be delivered after guest has checked in
            </span>
        </div>
    </label>
</div>

            </div>
        @endif

        <div class="settings-tab hidden rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-sm" data-settings-tab id="notice-tab">
            <h4 class="text-lg font-semibold text-slate-900">Notice period</h4>
            <div class="mt-4 space-y-3 text-sm text-slate-600">
                <x-input-label class="text-slate-700" for="notice_period">
                    Product must be ordered at least
                </x-input-label>
                <div class="flex items-center gap-2">
                    <input
                        min="0"
                        class="w-20 rounded-xl border border-slate-200 px-3 py-2 text-center text-sm font-semibold text-slate-700 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                        type="number"
                        name="specifics[notice_period]"
                        value="{{ (isset($product->specifics['notice_period']) && $product->specifics['notice_period']) ? $product->specifics['notice_period'] : 0 }}"
                        id="notice_period"
                    >
                    <span class="text-slate-500">day(s) before arrival</span>
                </div>
            </div>
        </div>

        @foreach($hotel->connections as $connection)
            @if($connection->key == 'resdiary_microsite_name')
                <div class="settings-tab hidden rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-sm" data-settings-tab id="resdiary-tab">
                    <h4 class="text-lg font-semibold text-slate-900">ResDiary</h4>
                    <div class="mt-4 space-y-6">
                        <div class="space-y-3">
                            <input type="hidden" name="specifics[requires_resdiary_booking]" value="0">
                            <x-fancy-checkbox
                                label="Require ResDiary booking"
                                name="specifics[requires_resdiary_booking]"
                                :isChecked="isset($product->specifics['requires_resdiary_booking']) ? $product->specifics['requires_resdiary_booking'] : false"
                            />
                        </div>
                        <div class="space-y-2">
                            <x-input-label class="text-slate-700" for="resdiary_promotion_id">ResDiary promotion ID</x-input-label>
                            <x-text-input
                                type="text"
                                name="specifics[resdiary_promotion_id]"
                                value="{{ (isset($product->specifics['resdiary_promotion_id']) && $product->specifics['resdiary_promotion_id']) ? $product->specifics['resdiary_promotion_id'] : null }}"
                                id="resdiary_promotion_id"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm"
                            />
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        @if($method == 'update')
            <div class="settings-tab hidden rounded-2xl border border-slate-200 bg-white/80 p-6 shadow-sm" data-settings-tab id="unavailability-tab">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h4 class="text-lg font-semibold text-slate-900">Periods of unavailability</h4>
                        <p class="text-sm text-slate-500">Block out moments when this experience cannot be fulfilled.</p>
                    </div>
                    <a class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100" id="triggerUnavailabilityModal" href="#">
                        <i data-lucide="plus" class="h-4 w-4"></i>
                        Add period
                    </a>
                </div>

                <ul class="mt-6 divide-y divide-slate-200 text-sm text-slate-600">
                    @forelse($product->unavailabilities as $unavailability)
                        <li class="flex flex-col gap-2 py-3 sm:flex-row sm:items-center sm:justify-between">
                            <span>
                                @if($unavailability->is_recurrent)
                                    {{ \App\Helpers\Date::formatToDayAndMonth($unavailability->start_at) }} - {{ \App\Helpers\Date::formatToDayAndMonth($unavailability->end_at) }} every year
                                @else
                                    {{ \App\Helpers\Date::formatToDayMonthYear($unavailability->start_at) }} - {{ \App\Helpers\Date::formatToDayMonthYear($unavailability->end_at) }}
                                @endif
                            </span>
                            <a href="/admin/unavailability/{{ $unavailability->id }}/delete" class="inline-flex items-center gap-2 text-sm font-semibold text-rose-600 transition hover:text-rose-700">
                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                Remove
                            </a>
                        </li>
                    @empty
                        <li class="py-4 text-slate-500">No unavailable periods configured.</li>
                    @endforelse
                </ul>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('[data-settings-button]');
        const tabs = document.querySelectorAll('[data-settings-tab]');
        const activeClasses = ['bg-white', 'text-slate-900', 'shadow-md', 'border-slate-200'];
        const inactiveClasses = ['bg-white/20', 'text-slate-600', 'border-transparent', 'shadow-none'];

        const activateTab = (targetId) => {
            tabs.forEach((tab) => {
                tab.classList.toggle('hidden', tab.id !== targetId);
            });

            buttons.forEach((button) => {
                if (button.dataset.target === targetId) {
                    button.classList.remove(...inactiveClasses);
                    button.classList.add(...activeClasses);
                } else {
                    button.classList.remove(...activeClasses);
                    button.classList.add(...inactiveClasses);
                }
            });
        };

        if (buttons.length && tabs.length) {
            activateTab(buttons[0].dataset.target);

            buttons.forEach((button) => {
                button.addEventListener('click', () => activateTab(button.dataset.target));
            });
        }
    });
</script>
