@if($type == 'standard' && $hotel->property_type == 'hotel')
    <div id="availability-tab" class="settings-tab rounded-3xl border border-slate-200 bg-white p-8 shadow-sm lg:col-span-2">
        {{-- Section heading --}}
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-slate-900">Availability</h3>
            <p class="mt-1 text-sm text-slate-500">
                Choose when guests can order this product during their stay.
            </p>
        </div>

        {{-- Availability moments (full width) --}}
        <div class="grid gap-5 sm:grid-cols-3">
            {{-- On Arrival --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-200 hover:shadow-md">
                <x-fancy-checkbox
                    label="Available on arrival"
                    name="specifics[on_arrival]"
                    :isChecked="(bool)($product->specifics['on_arrival'] ?? true)"
                />
                <p class="mt-2 text-xs text-slate-500">Allow guests to pre-book or order upon check-in.</p>
            </div>

            {{-- During Stay --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-200 hover:shadow-md">
                <x-fancy-checkbox
                    label="Available during stay"
                    name="specifics[during_stay]"
                    :isChecked="(bool)($product->specifics['during_stay'] ?? false)"
                />
                <p class="mt-2 text-xs text-slate-500">Guests can order anytime after arrival.</p>
            </div>

            {{-- On Departure --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-200 hover:shadow-md">
                <x-fancy-checkbox
                    label="Available on departure"
                    name="specifics[on_departure]"
                    :isChecked="(bool)($product->specifics['on_departure'] ?? false)"
                />
                <p class="mt-2 text-xs text-slate-500">Offer checkout-day treats or farewell gifts.</p>
            </div>
        </div>
    </div>
@else
    <input type="hidden" name="specifics[on_departure]" value="1">
    <input type="hidden" name="specifics[on_arrival]" value="1">
    <input type="hidden" name="specifics[during_stay]" value="1">
@endif
