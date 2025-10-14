@if($type == 'standard' && $hotel->property_type == 'hotel')
    <section class="rounded-3xl border border-slate-200 bg-gradient-to-br from-white via-indigo-50/30 to-slate-50 p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h4 class="text-lg font-semibold text-slate-900">Availability moments</h4>
                <p class="text-sm text-slate-500">
                    Choose when guests can order this product during their stay.
                </p>
            </div>
            <div class="inline-flex items-center gap-2 rounded-xl border border-indigo-100 bg-indigo-50/70 px-3 py-1.5 text-xs font-semibold text-indigo-600">
                <i data-lucide="clock" class="h-4 w-4"></i>
                Flexible scheduling
            </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            {{-- On arrival --}}
            <div class="rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm hover:shadow-md transition">
                <input type="hidden" name="specifics[on_arrival]" value="0">
                <x-fancy-checkbox
                    label="Available on arrival day"
                    name="specifics[on_arrival]"
                    :isChecked="isset($product->specifics['on_arrival']) ? $product->specifics['on_arrival'] : true"
                />
                <p class="mt-2 text-xs text-slate-500">Let guests pre-book or order when they check in.</p>
            </div>

            {{-- During stay --}}
            <div class="rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm hover:shadow-md transition">
                <input type="hidden" name="specifics[during_stay]" value="0">
                <x-fancy-checkbox
                    label="Available during stay"
                    name="specifics[during_stay]"
                    :isChecked="isset($product->specifics['during_stay']) ? $product->specifics['during_stay'] : false"
                />
                <p class="mt-2 text-xs text-slate-500">Guests can order at any time after arrival.</p>
            </div>

            {{-- On departure --}}
            <div class="rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm hover:shadow-md transition">
                <input type="hidden" name="specifics[on_departure]" value="0">
                <x-fancy-checkbox
                    label="Available on departure day"
                    name="specifics[on_departure]"
                    :isChecked="isset($product->specifics['on_departure']) ? $product->specifics['on_departure'] : false"
                />
                <p class="mt-2 text-xs text-slate-500">Offer farewell gifts or checkout-day extras.</p>
            </div>
        </div>
    </section>
@else
    <input type="hidden" name="specifics[on_departure]" value="1">
    <input type="hidden" name="specifics[on_arrival]" value="1">
    <input type="hidden" name="specifics[during_stay]" value="1">
@endif
