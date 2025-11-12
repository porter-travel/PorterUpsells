@php
    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
@endphp

@if($type === 'calendar')
    <div class="space-y-8 lg:col-span-2">
        <div>
            <h4 class="text-lg font-semibold text-slate-900">Scheduling</h4>
            <p class="text-sm text-slate-500">
                Define how many bookings can run concurrently and the interval between sessions.
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-3 items-end">
            {{-- Concurrent spaces --}}
            <div class="space-y-2">
                <label for="specifics[concurrent_availability]" class="block text-sm font-semibold text-slate-700">
                    Concurrent spaces
                </label>
                <input
                    required
                    type="number"
                    min="1"
                    name="specifics[concurrent_availability]"
                    value="{{ $product->specifics['concurrent_availability'] ?? 1 }}"
                    placeholder="1"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                />
            </div>

            {{-- Max bookings per day --}}
            <div class="space-y-2">
                <label for="specifics[max_bookings_per_day]" class="block text-sm font-semibold text-slate-700">
                    Max bookings per day
                </label>
                <input
                    required
                    type="number"
                    min="1"
                    name="specifics[max_bookings_per_day]"
                    value="{{ $product->specifics['max_bookings_per_day'] ?? '' }}"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                />
            </div>

            {{-- Booking interval --}}
            <div class="space-y-2">
                <label for="specifics[time_intervals]" class="block text-sm font-semibold text-slate-700">
                    Booking interval
                </label>
                <select
                    required
                    name="specifics[time_intervals]"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                >
                    <option value="" disabled @selected(!isset($product->specifics['time_intervals']))>Please select</option>
                    <option @selected(($product->specifics['time_intervals'] ?? null) === '30mins') value="30mins">30 minutes</option>
                    <option @selected(($product->specifics['time_intervals'] ?? null) === '1hr') value="1hr">1 hour</option>
                    <option @selected(($product->specifics['time_intervals'] ?? null) === '90mins') value="90mins">90 minutes</option>
                    <option @selected(($product->specifics['time_intervals'] ?? null) === '2hrs') value="2hrs">2 hours</option>
                    <option @selected(($product->specifics['time_intervals'] ?? null) === 'halfday') value="halfday">Half day</option>
                    <option @selected(($product->specifics['time_intervals'] ?? null) === 'fullday') value="fullday">Full day</option>
                </select>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
            <div class="grid grid-cols-1 gap-3 bg-slate-50 p-4 text-xs font-semibold uppercase tracking-widest text-slate-500 sm:grid-cols-3">
                <span>Available day</span>
                <span class="hidden sm:block">Start time</span>
                <span class="hidden sm:block">End time</span>
            </div>
            <ul class="divide-y divide-slate-200">
                @foreach($days as $day)
                    <li class="grid grid-cols-1 gap-4 px-4 py-3 sm:grid-cols-3 sm:items-center">
                        <div class="flex items-center gap-3">
                            <input type="hidden" name="specifics[available_{{ $day }}]" value="0">
                            <input
                                type="checkbox"
                                name="specifics[available_{{ $day }}]"
                                value="1"
                                id="available_{{ $day }}"
                                class="peer sr-only"
                                @checked($product->specifics['available_' . $day])
                            >
                            <span class="flex h-6 w-6 items-center justify-center rounded-md border border-slate-300 bg-white text-transparent transition peer-checked:border-indigo-500 peer-checked:bg-indigo-500 peer-checked:text-white">✓</span>
                            <label for="available_{{ $day }}" class="cursor-pointer text-sm font-semibold text-slate-700 capitalize">{{ $day }}</label>
                        </div>
                        <div class="space-y-1 sm:justify-self-center sm:text-left">
                            <x-text-input
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm"
                                type="time"
                                name="specifics[start_time_{{ $day }}]"
                                id="start_time_{{ $day }}"
                                :value="isset($product->specifics['start_time_' . $day]) ? $product->specifics['start_time_' . $day] : null"
                                placeholder="00:00"
                            />
                        </div>
                        <div class="space-y-1 sm:justify-self-end sm:text-left">
                            <x-text-input
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm"
                                type="time"
                                name="specifics[end_time_{{ $day }}]"
                                id="end_time_{{ $day }}"
                                :value="isset($product->specifics['end_time_' . $day]) ? $product->specifics['end_time_' . $day] : null"
                                placeholder="00:00"
                            />
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const days = @json($days);

            days.forEach(day => {
                const checkbox = document.getElementById(`available_${day}`);
                const startTime = document.getElementById(`start_time_${day}`);
                const endTime = document.getElementById(`end_time_${day}`);

                const updateRequired = () => {
                    const isChecked = checkbox?.checked ?? false;
                    if (startTime) startTime.required = isChecked;
                    if (endTime) endTime.required = isChecked;
                };

                updateRequired();

                checkbox?.addEventListener('change', updateRequired);
            });
        });
    </script>
@else
    <div class="space-y-4 lg:col-span-2">
        <div>
            <h4 class="text-lg font-semibold text-slate-900">Available days</h4>
            <p class="text-sm text-slate-500">Choose the days when this experience can be booked.</p>
        </div>
        <div class="grid grid-cols-2 gap-3 md:grid-cols-3">
            @foreach($days as $day)
                @php
                    $displayDay = $day === 'wednesday' ? 'Wed' : ucfirst($day);
                @endphp
                <label
                    class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm font-semibold capitalize text-slate-600 shadow-sm transition hover:border-indigo-200 hover:bg-slate-50 truncate cursor-pointer"
                    title="{{ ucfirst($day) }}"
                >
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
                            @checked($product->specifics['available_' . $day])
                        @endif
                    >
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-[4px] border border-slate-300 bg-white text-transparent transition-colors duration-150 ease-in-out peer-checked:border-indigo-500 peer-checked:bg-indigo-500 peer-checked:text-white">
                        ✓
                    </span>
                    <span class="text-slate-700 truncate w-full">{{ $displayDay }}</span>
                </label>
            @endforeach
        </div>
    </div>
@endif

