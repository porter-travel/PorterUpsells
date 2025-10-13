@php
    $typeLabel = match ($type) {
        'standard' => 'standard product',
        'calendar' => 'calendar experience',
        'restaurant' => 'restaurant experience',
        default => 'product',
    };
@endphp

<x-hotel-admin-layout :hotel="$hotel">
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-3">
                <p class="text-sm font-semibold uppercase tracking-widest text-indigo-500">Create a new {{ $typeLabel }}</p>
                <h1 class="text-3xl font-semibold text-slate-900 sm:text-4xl">Craft an experience guests will love</h1>
                <p class="max-w-2xl text-sm text-slate-500">Upload imagery, set pricing, and configure availability so this upsell feels perfectly on-brand for {{ $hotel->name }}.</p>
            </div>
            <div class="grid w-full max-w-sm gap-4 rounded-3xl border border-slate-200 bg-white/90 p-5 shadow-sm backdrop-blur">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Property</p>
                    <p class="mt-1 text-base font-semibold text-slate-900">{{ $hotel->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Product type</p>
                    <p class="mt-1 inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-1 text-sm font-semibold text-slate-700">{{ \Illuminate\Support\Str::title(str_replace('-', ' ', $type)) }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <section class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Core details</h2>
                    <p class="text-sm text-slate-500">Upload imagery, pricing, and descriptions for this experience.</p>
                </div>
                <div class="inline-flex items-center gap-2 rounded-xl border border-indigo-100 bg-indigo-50/80 px-3 py-1.5 text-xs font-semibold text-indigo-600">
                    <i data-lucide="shield-check" class="h-4 w-4"></i>
                    Autosave enabled
                </div>
            </div>
            <div class="mt-8 space-y-8">
                <form enctype="multipart/form-data" method="post" action="/admin/product/store" class="space-y-8">
                    @csrf
                    <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                    <input type="hidden" name="type" value="{{ $type }}">

                    <div class="space-y-8">
                        <div class="space-y-8">
                            @include('admin.product.partials.core-fields', ['product' => new \App\Models\Product(), 'method' => 'create'])
                        </div>

                        <section class="rounded-3xl border border-slate-200 bg-gradient-to-br from-white via-sky-50 to-indigo-50 p-6 shadow-sm sm:p-8">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900">Experience settings</h3>
                                    <p class="text-sm text-slate-500">Control availability, storage, and fulfilment rules for this offer.</p>
                                </div>
                                <div class="inline-flex items-center gap-2 rounded-xl border border-white/70 bg-white/40 px-3 py-1.5 text-xs font-semibold text-indigo-600">
                                    <i data-lucide="sparkles" class="h-4 w-4"></i>
                                    Smart defaults applied
                                </div>
                            </div>
                            <div class="mt-6 space-y-6">
                                @include('admin.product.partials.specifics', ['method' => 'create', 'type' => $type])
                            </div>
                        </section>

                        <section id="variantContainer" class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900">Variations</h3>
                                    <p class="text-sm text-slate-500">Offer size, timing, or package options for guests to choose from.</p>
                                </div>
                                <button data-id="0" class="add-item inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100" role="button">
                                    <i data-lucide="plus" class="h-4 w-4"></i>
                                    Add a variation
                                </button>
                            </div>
                            <div id="variations-list" class="mt-6 hidden"></div>
                        </section>
                    </div>

                    <x-primary-button class="w-full justify-center text-base">Publish experience</x-primary-button>
                </form>
            </div>
        </section>
    </div>
</x-hotel-admin-layout>
