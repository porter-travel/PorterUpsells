@if($type == 'standard' && $hotel->property_type == 'hotel')
    <div id="availability-tab" class="settings-tab lg:col-span-2">
        {{-- Section heading --}}
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-slate-900">Availability moments</h3>
            <p class="mt-1 text-sm text-slate-500">
                Choose when guests can order this product during their stay.
            </p>
        </div>

        {{-- Availability checkboxes --}}
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            {{-- On Arrival --}}
            <label class="group flex items-start gap-3 rounded-2xl border border-slate-200 bg-white/80 px-4 py-4 text-sm font-medium text-slate-700 shadow-sm transition hover:border-indigo-200 hover:bg-slate-50 peer-checked:bg-indigo-50 cursor-pointer">
                <input type="hidden" name="specifics[on_arrival]" value="0">
                <input
                    type="checkbox"
                    name="specifics[on_arrival]"
                    value="1"
                    id="on_arrival"
                    class="peer sr-only"
                    @checked($product->specifics['on_arrival'] ?? true)
                >
                <span class="flex h-6 w-6 items-center justify-center rounded-md border border-slate-300 bg-white text-transparent transition
                    peer-checked:border-indigo-500 peer-checked:bg-indigo-500 peer-checked:text-white">
                    ✓
                </span>
                <div class="flex flex-col">
                    <span class="font-semibold text-slate-800 group-hover:text-slate-900">Available on arrival</span>
                    <span class="text-xs text-slate-500 group-hover:text-slate-600">
                        Allow guests to pre-book or order upon check-in.
                    </span>
                </div>
            </label>

            {{-- During Stay --}}
            <label class="group flex items-start gap-3 rounded-2xl border border-slate-200 bg-white/80 px-4 py-4 text-sm font-medium text-slate-700 shadow-sm transition hover:border-indigo-200 hover:bg-slate-50 cursor-pointer peer-checked:bg-indigo-50">
                <input type="hidden" name="specifics[during_stay]" value="0">
                <input
                    type="checkbox"
                    name="specifics[during_stay]"
                    value="1"
                    id="during_stay"
                    class="peer sr-only"
                    @checked($product->specifics['during_stay'] ?? false)
                >
                <span class="flex h-6 w-6 items-center justify-center rounded-md border border-slate-300 bg-white text-transparent transition
                    peer-checked:border-indigo-500 peer-checked:bg-indigo-500 peer-checked:text-white">
                    ✓
                </span>
                <div class="flex flex-col">
                    <span class="font-semibold text-slate-800 group-hover:text-slate-900">Available during stay</span>
                    <span class="text-xs text-slate-500 group-hover:text-slate-600">
                        Guests can order anytime after arrival.
                    </span>
                </div>
            </label>

            {{-- On Departure --}}
            <label class="group flex items-start gap-3 rounded-2xl border border-slate-200 bg-white/80 px-4 py-4 text-sm font-medium text-slate-700 shadow-sm transition hover:border-indigo-200 hover:bg-slate-50 cursor-pointer peer-checked:bg-indigo-50">
                <input type="hidden" name="specifics[on_departure]" value="0">
                <input
                    type="checkbox"
                    name="specifics[on_departure]"
                    value="1"
                    id="on_departure"
                    class="peer sr-only"
                    @checked($product->specifics['on_departure'] ?? false)
                >
                <span class="flex h-6 w-6 items-center justify-center rounded-md border border-slate-300 bg-white text-transparent transition
                    peer-checked:border-indigo-500 peer-checked:bg-indigo-500 peer-checked:text-white">
                    ✓
                </span>
                <div class="flex flex-col">
                    <span class="font-semibold text-slate-800 group-hover:text-slate-900">Available on departure</span>
                    <span class="text-xs text-slate-500 group-hover:text-slate-600">
                        Offer checkout-day treats or farewell gifts.
                    </span>
                </div>
            </label>
        </div>
    </div>
@else
    <input type="hidden" name="specifics[on_departure]" value="1">
    <input type="hidden" name="specifics[on_arrival]" value="1">
    <input type="hidden" name="specifics[during_stay]" value="1">
@endif
