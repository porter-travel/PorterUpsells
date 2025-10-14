@if($type == 'standard' && $hotel->property_type == 'hotel')
    <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-6 sm:p-8">
        <div class="mb-5">
            <h4 class="text-lg font-semibold text-slate-900">Availability moments</h4>
            <p class="text-sm text-slate-500">
                Choose when guests can order this product during their stay.
            </p>
        </div>

        <div class="grid gap-5 sm:grid-cols-3">
            {{-- Arrival --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:border-indigo-200 hover:shadow-md transition">
                <input type="hidden" name="specifics[on_arrival]" value="0">
                <x-fancy-checkbox
                    label="Available on arrival"
                    name="specifics[on_arrival]"
                    :isChecked="isset($product->specifics['on_arrival']) ? $product->specifics['on_arrival'] : true"
                />
                <p class="mt-2 text-xs text-slate-500">Allow guests to pre-book or order upon check-in.</p>
            </div>

            {{-- During stay --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:border-indigo-200 hover:shadow-md transition">
                <input type="hidden" name="specifics[during_stay]" value="0">
                <x-fancy-checkbox
                    label="Available during stay"
                    name="specifics[during_stay]"
                    :isChecked="isset($product->specifics['during_stay']) ? $product->specifics['during_stay'] : false"
                />
                <p class="mt-2 text-xs text-slate-500">Guests can order anytime after arrival.</p>
            </div>

            {{-- Departure --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:border-indigo-200 hover:shadow-md transition">
                <input type="hidden" name="specifics[on_departure]" value="0">
                <x-fancy-checkbox
                    label="Available on departure"
                    name="specifics[on_departure]"
                    :isChecked="isset($product->specifics['on_departure']) ? $product->specifics['on_departure'] : false"
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
