@if($type == 'standard' && $hotel->property_type == 'hotel')
    <div id="availability-tab" class="settings-tab rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
        {{-- Section heading --}}
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-slate-900">Availability & Scheduling</h3>
            <p class="mt-1 text-sm text-slate-500">
                Define when this product can be booked or ordered by guests.
            </p>
        </div>

        <div class="grid gap-10 grid-cols-1">
            {{-- Availability Moments --}}
            <div>
                <h4 class="text-base font-semibold text-slate-900">Availability moments</h4>
                <p class="mt-1 text-sm text-slate-500">
                    Choose when guests can order this product during their stay.
                </p>

                <div class="mt-6 grid gap-5 sm:grid-cols-3">
                    {{-- On Arrival --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-200 hover:shadow-md">
                        <x-fancy-checkbox
                            label="Available on arrival"
                            name="specifics[on_arrival]"
                            :isChecked="$product->specifics['on_arrival'] ?? true"
                        />
                        <p class="mt-2 text-xs text-slate-500">Allow guests to pre-book or order upon check-in.</p>
                    </div>

                    {{-- During Stay --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-200 hover:shadow-md">
                        <x-fancy-checkbox
                            label="Available during stay"
                            name="specifics[during_stay]"
                            :isChecked="$product->specifics['during_stay'] ?? false"
                        />
                        <p class="mt-2 text-xs text-slate-500">Guests can order anytime after arrival.</p>
                    </div>

                    {{-- On Departure --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-200 hover:shadow-md">
                        <x-fancy-checkbox
                            label="Available on departure"
                            name="specifics[on_departure]"
                            :isChecked="$product->specifics['on_departure'] ?? false"
                        />
                        <p class="mt-2 text-xs text-slate-500">Offer checkout-day treats or farewell gifts.</p>
                    </div>
                </div>
            </div>

            {{-- Available Days --}}
            <div class="mt-10">
                <h4 class="text-base font-semibold text-slate-900">Available days</h4>
                <p class="mt-1 text-sm text-slate-500">Choose which days this experience can be booked.</p>

                <div class="mt-6 grid grid-cols-2 gap-3 md:grid-cols-3">
                    @foreach (['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
                        <label class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium capitalize text-slate-700 shadow-sm transition hover:border-indigo-200 hover:bg-slate-50">
                            <input type="hidden" name="specifics[available_{{ $day }}]" value="0">
                            <input
                                type="checkbox"
                                name="specifics[available_{{ $day }}]"
                                value="1"
                                id="{{ $day }}"
                                class="peer sr-only"
                                @if($method == 'create')
                                    checked
                                @else
                                    @checked(!empty($product->specifics['available_' . $day]))
                                @endif
                            >
                            <span class="flex h-6 w-6 items-center justify-center rounded-md border border-slate-300 bg-white text-transparent transition peer-checked:border-indigo-500 peer-checked:bg-indigo-500 peer-checked:text-white">✓</span>
                            <span>{{ ucfirst($day) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@else
    <input type="hidden" name="specifics[on_departure]" value="1">
    <input type="hidden" name="specifics[on_arrival]" value="1">
    <input type="hidden" name="specifics[during_stay]" value="1">
@endif
