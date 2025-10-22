@props([
    'startDate',
    'endDate',
    'status' => null,
    'exportLink' => null,
    'quickRanges' => [],
])

@php
    $formId = $attributes->get('id') ?? 'filterBarForm';
    $exportLinkId = $exportLink ? $formId . '_exportLink' : null;
@endphp

<form
    id="{{ $formId }}"
    method="get"
    {{ $attributes->except('id')->class('grid gap-6 lg:flex lg:flex-wrap lg:items-end lg:justify-between') }}
>
    <div class="flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-end">
        <div class="space-y-2">
            <label class="text-xs font-semibold uppercase tracking-widest text-slate-500" for="startDatePicker">Start date</label>
            <x-text-input
                id="startDatePicker"
                type="date"
                name="start_date"
                value="{{ $startDate }}"
                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
        </div>

        <div class="space-y-2">
            <label class="text-xs font-semibold uppercase tracking-widest text-slate-500" for="endDatePicker">End date</label>
            <x-text-input
                id="endDatePicker"
                type="date"
                name="end_date"
                value="{{ $endDate }}"
                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
        </div>

        @if(!empty($quickRanges))
            <div class="space-y-2">
                <span class="block text-xs font-semibold uppercase tracking-widest text-slate-500">Quick ranges</span>
                <div class="flex flex-wrap gap-2">
                    @foreach($quickRanges as $range)
                        @php
                            $isActive = ($startDate === $range['start']) && ($endDate === $range['end']);
                            $buttonClasses = $isActive
                                ? 'border-indigo-500 bg-indigo-500 text-white shadow-sm hover:bg-indigo-600'
                                : 'border-slate-200 bg-white text-slate-600 hover:border-indigo-200 hover:text-indigo-600';
                        @endphp
                        <button
                            type="button"
                            data-date-range-button
                            data-start-date="{{ $range['start'] }}"
                            data-end-date="{{ $range['end'] }}"
                            aria-pressed="{{ $isActive ? 'true' : 'false' }}"
                            class="rounded-full border px-4 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-white {{ $buttonClasses }}"
                        >
                            {{ $range['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        @isset($status)
            <div class="space-y-2">
                <label class="text-xs font-semibold uppercase tracking-widest text-slate-500" for="statusFilter">Status</label>
                <select
                    id="statusFilter"
                    name="status"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="all">All</option>

                    @foreach(\App\Enums\OrderStatus::getValues() as $value)
                        @continue($value === 'complete')
                        <option value="{{ $value }}" @selected(($status ?? 'all') === $value)>{{ ucfirst($value) }}</option>
                    @endforeach
                </select>
            </div>
        @endisset

        <div class="pb-1">
            <x-primary-button type="submit" class="px-5 py-2">
                <i data-lucide="sliders-horizontal" class="h-4 w-4"></i>
                Filter
            </x-primary-button>
        </div>
    </div>

    @isset($exportLink)
        <div class="flex items-center gap-3">
            <a
                id="{{ $exportLinkId }}"
                href="{{ $exportLink }}"
                class="inline-flex items-center gap-2 rounded-2xl border border-indigo-100 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 shadow-sm transition hover:bg-indigo-100"
            >
                <i data-lucide="download" class="h-4 w-4"></i>
                Export CSV
            </a>
        </div>
    @endisset

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById(@json($formId));

            if (!form) {
                return;
            }

            const startInput = form.querySelector('input[name="start_date"]');
            const endInput = form.querySelector('input[name="end_date"]');
            const quickRangeButtons = form.querySelectorAll('[data-date-range-button]');

            @if($exportLink)
                const exportButton = document.getElementById(@json($exportLinkId));

                const updateExportUrl = () => {
                    if (!exportButton) {
                        return;
                    }

                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData).toString();
                    exportButton.href = '{{ $exportLink }}' + (params ? `?${params}` : '');
                };

                updateExportUrl();

                form.addEventListener('submit', updateExportUrl);
                form.querySelectorAll('input, select').forEach(element => {
                    element.addEventListener('change', updateExportUrl);
                });
            @endif

            if (quickRangeButtons.length && startInput && endInput) {
                quickRangeButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const startDate = button.dataset.startDate;
                        const endDate = button.dataset.endDate;

                        if (startDate) {
                            startInput.value = startDate;
                            startInput.dispatchEvent(new Event('change', { bubbles: true }));
                        }

                        if (endDate) {
                            endInput.value = endDate;
                            endInput.dispatchEvent(new Event('change', { bubbles: true }));
                        }

                        if (typeof form.requestSubmit === 'function') {
                            form.requestSubmit();
                        } else {
                            form.submit();
                        }
                    });
                });
            }
        });
    </script>
</form>
