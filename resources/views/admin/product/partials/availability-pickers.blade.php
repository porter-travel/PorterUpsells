@if($type == 'standard' && $hotel->property_type == 'hotel')
    <div id="availability-tab" class="settings-tab p-0 lg:col-span-2">
        {{-- Section heading --}}
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-slate-900">Availability moments</h3>
            <p class="mt-1 text-sm text-slate-500">
                Choose when guests can order this product during their stay.
            </p>
        </div>

        {{-- Availability options --}}
        <div class="grid gap-4 sm:grid-cols-3">
            {{-- On Arrival --}}
            <label class="group flex flex-col rounded-2xl border border-slate-200 bg-white/80 p-5 transition hover:border-indigo-200 hover:bg-white hover:shadow-sm cursor-pointer">
                <x-fancy-checkbox
                    label="Available on arrival"
                    name="specifics[on_arrival]"
                    :isChecked="(bool)($product->specifics['on_arrival'] ?? true)"
                />
                <p class="mt-2 text-sm text-slate-500 group-hover:text-slate-600">
                    Allow guests to pre-book or order upon check-in.
                </p>
            </label>

            {{-- During Stay --}}
            <label class="group flex flex-col rounded-2xl border border-slate-200 bg-white/80 p-5 transition hover:border-indigo-200 hover:bg-white hover:shadow-sm cursor-pointer">
                <x-fancy-checkbox
                    label="Available during stay"
                    name="specifics[during_stay]"
                    :isChecked="(bool)($product->specifics['during_stay'] ?? false)"
                />
                <p class="mt-2 text-sm text-slate-500 group-hover:text-slate-600">
                    Guests can order anytime after arrival.
                </p>
            </label>

            {{-- On Departure --}}
            <label class="group flex flex-col rounded-2xl border border-slate-200 bg-white/80 p-5 transition hover:border-indigo-200 hover:bg-white hover:shadow-sm cursor-pointer">
                <x-fancy-checkbox
                    label="Available on departure"
                    name="specifics[on_departure]"
                    :isChecked="(bool)($product->specifics['on_departure'] ?? false)"
                />
                <p class="mt-2 text-sm text-slate-500 group-hover:text-slate-600">
                    Offer checkout-day treats or farewell gifts.
                </p>
            </label>
        </div>
    </div>
@else
    <input type="hidden" name="specifics[on_departure]" value="1">
    <input type="hidden" name="specifics[on_arrival]" value="1">
    <input type="hidden" name="specifics[during_stay]" value="1">
@endif
