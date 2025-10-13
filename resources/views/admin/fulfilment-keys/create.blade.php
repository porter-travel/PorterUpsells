@php
    $activeHotel = auth()->user()?->hotels->first();
    $selectedHotels = collect(old('hotel', []))->map(fn ($id) => (int) $id)->all();
@endphp

<x-hotel-admin-layout :hotel="$activeHotel">
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-2">
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500">Fulfilment access</p>
                <h1 class="text-3xl font-semibold text-slate-900 sm:text-4xl">Create fulfilment key</h1>
                <p class="max-w-2xl text-sm text-slate-500">Generate a dedicated access key and choose which properties can use it to complete orders outside of the admin portal.</p>
            </div>
            <div class="w-full max-w-xs rounded-3xl border border-slate-200 bg-white/80 p-5 text-sm text-slate-500 shadow-sm">
                <div class="inline-flex items-center gap-2 rounded-xl border border-amber-100 bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-600">
                    <i data-lucide="info" class="h-4 w-4"></i>
                    Heads up
                </div>
                <p class="mt-3 text-sm">Keys are long-lived access tokens. Rotate them regularly and only share with trusted partners.</p>
            </div>
        </div>
    </x-slot>

    <section class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
        @if($hotels->isEmpty())
            <div class="flex flex-col items-center justify-center gap-4 rounded-3xl border border-dashed border-slate-300 bg-white/80 p-12 text-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                    <i data-lucide="building-2" class="h-6 w-6"></i>
                </div>
                <div class="space-y-2">
                    <h2 class="text-xl font-semibold text-slate-900">No properties available</h2>
                    <p class="text-sm text-slate-500">You need at least one property to create a fulfilment key. Contact your administrator to gain access.</p>
                </div>
            </div>
        @else
            <form method="post" action="{{ route('fulfilment-keys.store') }}" class="space-y-10">
                @csrf

                <div class="grid gap-8 lg:grid-cols-3">
                    <div class="space-y-6 lg:col-span-2">
                        <div class="space-y-2">
                            <label for="name" class="text-sm font-semibold text-slate-800">Key name</label>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                placeholder="e.g. Housekeeping access"
                                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 placeholder-slate-400 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                            >
                            <p class="text-xs text-slate-500">This label appears in your fulfilment key list to help you identify who the access was shared with.</p>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label for="expires_at" class="text-sm font-semibold text-slate-800">Expires on</label>
                                <span class="text-xs font-medium uppercase tracking-widest text-slate-400">Optional</span>
                            </div>
                            <input
                                id="expires_at"
                                type="date"
                                name="expires_at"
                                value="{{ old('expires_at') }}"
                                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 shadow-sm transition focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                            >
                            <p class="text-xs text-slate-500">Set an expiry date to automatically invalidate this key after a specific day.</p>
                            <x-input-error :messages="$errors->get('expires_at')" class="mt-2" />
                        </div>
                    </div>

                    <aside class="rounded-3xl border border-slate-200 bg-slate-50/70 p-6 text-sm text-slate-600 shadow-sm">
                        <div class="space-y-3">
                            <h2 class="text-base font-semibold text-slate-900">Sharing best practices</h2>
                            <ul class="list-disc space-y-2 pl-5">
                                <li>Only enable the properties this partner needs access to.</li>
                                <li>Deactivate or delete keys when access is no longer required.</li>
                                <li>Use descriptive names so your team can audit keys quickly.</li>
                            </ul>
                        </div>
                    </aside>
                </div>

                <div class="space-y-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-baseline sm:justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Select properties</h2>
                            <p class="text-sm text-slate-500">Choose which hotels this fulfilment key can manage orders for.</p>
                        </div>
                        <span class="text-xs font-medium uppercase tracking-widest text-slate-400">At least one required</span>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($hotels as $hotel)
                            @php
                                $isSelected = in_array($hotel->id, $selectedHotels, true);
                            @endphp
                            <label
                                for="hotel-{{ $hotel->id }}"
                                @class([
                                    'relative flex items-start gap-3 rounded-2xl border bg-white/80 p-4 shadow-sm transition hover:border-indigo-200 focus-within:border-indigo-300',
                                    'border-indigo-300 ring-1 ring-indigo-100' => $isSelected,
                                ])
                            >
                                <input
                                    id="hotel-{{ $hotel->id }}"
                                    type="checkbox"
                                    name="hotel[]"
                                    value="{{ $hotel->id }}"
                                    @checked($isSelected)
                                    @if($loop->first) required @endif
                                    class="mt-1 h-4 w-4 shrink-0 rounded border-slate-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1"
                                >
                                <div class="space-y-1">
                                    <p class="text-sm font-semibold text-slate-900">{{ $hotel->name }}</p>
                                    @if($hotel->address)
                                        <p class="text-xs text-slate-500">{{ $hotel->address }}</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-slate-500">The key will be available immediately after creation and can be revoked at any time.</p>
                    <x-primary-button class="w-full justify-center text-base sm:w-auto">Create fulfilment key</x-primary-button>
                </div>
            </form>
        @endif
    </section>
</x-hotel-admin-layout>
