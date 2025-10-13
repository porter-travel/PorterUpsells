<x-hotel-admin-layout :hotel="$hotel">
    <x-slot name="header">
        <div class="space-y-2">
            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500">Branding & identity</p>
            <h1 class="text-3xl font-semibold text-slate-900 sm:text-4xl">Keep {{ $hotel->name }} perfectly on-brand</h1>
            <p class="max-w-2xl text-sm text-slate-500">Update logos, imagery, and colour palettes so every guest touchpoint feels aligned with your property.</p>
        </div>
    </x-slot>

    <section class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
        @include('admin.hotel.partials.branding-settings')
    </section>

</x-hotel-admin-layout>
