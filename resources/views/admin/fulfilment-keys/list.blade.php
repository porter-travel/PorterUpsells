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
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-widest text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Properties</th>
                        <th class="px-6 py-4">Key</th>
                        <th class="px-6 py-4">Expires at</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                    @foreach($keys as $k => $key)
                        <tr class="transition hover:bg-slate-50/70">
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
                                    <input class="booking-link w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600" type="text" readonly value="{{ env('APP_URL') }}/fulfilment/{{ $k }}">
                                    <button type="button" class="copy-label inline-flex items-center gap-1 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100" onclick="copyToClipboard(this)">
                                        <i data-lucide="copy" class="h-3.5 w-3.5"></i>
                                        Copy
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $key['expires_at'] ? $key['expires_at'] : 'Not expiring' }}</td>
                            <td class="px-6 py-4 text-right">
                                <form method="post" action="{{ route('fulfilment-keys.delete', ['key' => $k]) }}" class="inline-flex items-center gap-2">
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
    </script>
</x-hotel-admin-layout>
