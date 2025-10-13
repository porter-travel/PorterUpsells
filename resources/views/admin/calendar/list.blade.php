<x-hotel-admin-layout :hotel="$hotel">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendar Orders for: ') . $hotel->name }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="get">
                        @csrf
                        <div class="flex flex-wrap items-end gap-4 pb-6">
                            <label class="flex flex-col text-sm font-semibold text-slate-600">
                                <span class="mb-1 text-xs font-semibold uppercase tracking-widest text-slate-500">Start Date</span>
                                <input
                                    type="date"
                                    name="start_date"
                                    value="{{ $startDate }}"
                                    class="rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500"
                                >
                            </label>

                            <label class="flex flex-col text-sm font-semibold text-slate-600">
                                <span class="mb-1 text-xs font-semibold uppercase tracking-widest text-slate-500">End Date</span>
                                <input
                                    type="date"
                                    name="end_date"
                                    value="{{ $endDate }}"
                                    class="rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500"
                                >
                            </label>

                            <x-secondary-button type="submit" class="h-10 px-6">Filter</x-secondary-button>
                        </div>
                    </form>

                    <div id="calendar-page-root" class="mt-6" data-calendar-page></div>
                </div>
            </div>
        </div>
    </div>
</x-hotel-admin-layout>

<script type="application/json" id="calendar-page-data">
    {!! json_encode($calendarData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!}
</script>
