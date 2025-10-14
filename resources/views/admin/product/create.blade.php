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
            {{-- Header text --}}
            <div class="space-y-3">
                <p class="text-sm font-semibold uppercase tracking-widest text-indigo-500">
                    Create a new {{ $typeLabel }}
                </p>
                <h1 class="text-3xl font-semibold text-slate-900 sm:text-4xl">
                    Add a product guests will love
                </h1>
                <p class="max-w-2xl text-sm text-slate-500">
                    Upload images, pricing, and availability to build an on-brand upsell for <span class="font-semibold text-slate-700">{{ $hotel->name }}</span>.
                </p>
            </div>

            {{-- Sidebar summary card --}}
            <div class="grid w-full max-w-sm gap-5 rounded-3xl border border-slate-200 bg-white/90 p-5 shadow-sm backdrop-blur">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Property</p>
                    <p class="mt-1 text-base font-semibold text-slate-900">{{ $hotel->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Product type</p>
                    <p class="mt-1 inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-1 text-sm font-medium text-slate-700">
                        {{ \Illuminate\Support\Str::title(str_replace('-', ' ', $type)) }}
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10">
        {{-- Core Details --}}
        <section class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Core details</h2>
                    <p class="text-sm text-slate-500">Set up the visuals, pricing, and description for this product.</p>
                </div>
                <div class="inline-flex items-center gap-2 rounded-xl border border-indigo-100 bg-indigo-50/80 px-3 py-1.5 text-xs font-semibold text-indigo-600">
                    <i data-lucide="shield-check" class="h-4 w-4"></i>
                    Autosave enabled
                </div>
            </div>

            <div class="mt-8">
                <form enctype="multipart/form-data" method="post" action="/admin/product/store" class="space-y-10">
                    @csrf
                    <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                    <input type="hidden" name="type" value="{{ $type }}">

                    {{-- Core fields --}}
                    <div class="space-y-8">
                        @include('admin.product.partials.core-fields', [
                            'product' => new \App\Models\Product(),
                            'method' => 'create'
                        ])
                    </div>

                   {{-- Experience Settings --}}
<section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-slate-900">Experience settings</h3>
            <p class="text-sm text-slate-500">
                Control availability, scheduling, and fulfilment rules for this product.
            </p>
        </div>
        <div class="inline-flex items-center gap-2 rounded-xl border border-indigo-100 bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-600">
            <i data-lucide="sparkles" class="h-4 w-4"></i>
            Smart defaults applied
        </div>
    </div>

    <div class="mt-6 space-y-8">
        {{-- Include partial --}}
        @include('admin.product.partials.specifics', [
            'method' => 'create',
            'type' => $type
        ])
    </div>
</section>


                    {{-- Variations --}}
                    <section id="variantContainer" class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">Variations</h3>
                                <p class="text-sm text-slate-500">Add options such as sizes, time slots, or packages to offer guests flexibility.</p>
                            </div>
                            <button data-id="0" type="button"
                                class="add-item inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100">
                                <i data-lucide="plus" class="h-4 w-4"></i>
                                Add variation
                            </button>
                        </div>

                        <div id="variations-list" class="mt-6 hidden"></div>
                    </section>

                    <x-primary-button class="w-full justify-center text-base">
                        Publish experience
                    </x-primary-button>
                </form>
            </div>
        </section>
    </div>
</x-hotel-admin-layout>
