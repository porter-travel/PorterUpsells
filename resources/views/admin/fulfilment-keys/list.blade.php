@php
    $activeHotel = auth()->user()?->hotels->first();
@endphp

<x-hotel-admin-layout :hotel="$activeHotel">
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-2">
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500">Fulfilment access</p>
                <h1 class="text-3xl font-semibold text-slate-900 sm:text-4xl">Manage fulfilment keys</h1>
                <p class="max-w-2xl text-sm text-slate-500">Share secure access tokens with your fulfilment partners so they can mark orders as complete without entering the admin portal.</p>
            </div>
            <a href="{{ route('fulfilment-keys.create') }}" class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Create fulfilment key
            </a>
        </div>
    </x-slot>

    <section class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
        @if(is_countable($keys) && count($keys) > 0)
            <div class="grid gap-8 lg:grid-cols-[minmax(0,1.25fr)_minmax(0,1fr)]">
                <div class="overflow-hidden rounded-2xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-widest text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Properties</th>
                            <th class="px-6 py-4">Link</th>
                            <th class="px-6 py-4">Expires</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach($keys as $k => $key)
                            @php
                                $previewUrl = route('fulfilment', ['key' => $k]);
                            @endphp
                            <tr class="fulfilment-row cursor-pointer transition hover:bg-slate-50/80" data-preview-url="{{ $previewUrl }}">
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $key['name'] }}</td>
                                <td class="px-6 py-4">
                                    <ul class="space-y-1 text-sm text-slate-500">
                                        @foreach($key['hotels'] as $hotel)
                                            <li>{{ $hotel['name'] }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <input class="booking-link w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600" type="text" readonly value="{{ $previewUrl }}">
                                        <button type="button" class="copy-label inline-flex items-center gap-1 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100" onclick="copyToClipboard(this)">
                                            <i data-lucide="copy" class="h-3.5 w-3.5"></i>
                                            Copy
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $key['expires_at'] ? $key['expires_at'] : 'Not expiring' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <form method="post" action="{{ route('fulfilment-keys.delete', ['key' => $k]) }}" class="inline-flex items-center gap-2" onclick="event.stopPropagation();">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="key" value="{{ $k }}">
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-1.5 text-sm font-semibold text-rose-600 transition hover:border-rose-300 hover:bg-rose-100">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex h-full flex-col gap-4 rounded-2xl border border-slate-200 bg-slate-50/80 p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500">Preview</p>
                            <h2 class="text-lg font-semibold text-slate-900">Fulfilment checklist</h2>
                            <p class="text-xs text-slate-500">Select a key from the table to preview what partners will see.</p>
                        </div>
                        <a id="fulfilmentPreviewLink" href="#" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-indigo-200 hover:text-indigo-600">
                            <i data-lucide="external-link" class="h-3.5 w-3.5"></i>
                            Open full view
                        </a>
                    </div>
                    <div class="relative flex-1 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-inner">
                        <iframe id="fulfilmentPreview" title="Fulfilment preview" class="h-full w-full min-h-[460px]" src=""></iframe>
                    </div>
                </div>
            </div>
        @else
            <div class="flex flex-col items-center justify-center gap-4 rounded-3xl border border-dashed border-slate-300 bg-white/70 p-12 text-center shadow-sm">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-indigo-50 text-indigo-500">
                    <i data-lucide="key-round" class="h-6 w-6"></i>
                </div>
                <div class="space-y-2">
                    <h3 class="text-xl font-semibold text-slate-900">No keys yet</h3>
                    <p class="max-w-md text-sm text-slate-500">Generate a fulfilment key to allow partners to complete orders without needing a full EMS login.</p>
                </div>
                <a href="{{ route('fulfilment-keys.create') }}" class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Create fulfilment key
                </a>
            </div>
        @endif
    </section>

    <style>
        .fulfilment-row.active {
            background-color: rgba(79, 70, 229, 0.08);
        }

        .fulfilment-row.active td {
            color: #1e293b;
        }
    </style>

    <script>
        function copyToClipboard(element) {
            const input = element.previousElementSibling;
            if (input && input.value) {
                const tempTextArea = document.createElement('textarea');
                tempTextArea.value = input.value;
                document.body.appendChild(tempTextArea);
                tempTextArea.select();
                try {
                    document.execCommand('copy');
                    element.innerHTML = '<span class="inline-flex items-center gap-1"><i data-lucide="check" class="h-3.5 w-3.5"></i>Copied</span>';
                    setTimeout(() => {
                        element.innerHTML = '<span class="inline-flex items-center gap-1"><i data-lucide="copy" class="h-3.5 w-3.5"></i>Copy</span>';
                        if (window.lucide) {
                            window.lucide.createIcons();
                        }
                    }, 2000);
                } catch (err) {
                    console.error('Unable to copy', err);
                }
                document.body.removeChild(tempTextArea);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const rows = document.querySelectorAll('.fulfilment-row');
            const previewFrame = document.getElementById('fulfilmentPreview');
            const previewLink = document.getElementById('fulfilmentPreviewLink');

            const activateRow = (row) => {
                if (!row || !previewFrame || !previewLink) {
                    return;
                }

                rows.forEach(r => {
                    r.classList.remove('active');
                });

                row.classList.add('active');

                const url = row.getAttribute('data-preview-url');
                if (url) {
                    previewFrame.src = url;
                    previewLink.href = url;
                }
            };

            rows.forEach(row => {
                row.addEventListener('click', () => activateRow(row));
            });

            if (rows.length > 0) {
                activateRow(rows[0]);
            }
        });
    </script>
</x-hotel-admin-layout>
