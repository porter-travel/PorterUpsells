@if(count($products) > 0)
    <ul class="sortable-list space-y-3 sm:space-y-4">
        @foreach($products as $product)
            <li data-product-id="{{ $product->id }}"
                class="sortable-item group flex items-center gap-4 rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                {{-- Drag handle --}}
                <div draggable="true"
                     class="handle flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-400 transition group-hover:border-indigo-200 group-hover:text-indigo-500 cursor-grab active:cursor-grabbing">
                    <i data-lucide="grip-vertical" class="h-5 w-5"></i>
                </div>

               {{-- Image with vertical spacing --}}
<div class="py-1">
    <a href="/admin/hotel/{{ $hotel->id }}/product/{{ $product->id }}/edit"
       class="flex h-20 w-24 shrink-0 items-center justify-center rounded-xl bg-white">
        <div class="flex h-full w-full items-center justify-center overflow-hidden rounded-lg">
            @include('hotel.partials.product-image', ['item' => $product])
        </div>
    </a>
</div>



                {{-- Main info --}}
                <div class="flex flex-1 flex-col justify-center sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                    <div class="flex-1 space-y-1.5">
                        <a href="/admin/hotel/{{ $hotel->id }}/product/{{ $product->id }}/edit" class="hover:underline">
                            <p class="text-base font-semibold text-slate-900">{{ $product->name }}</p>
                        </a>

                        <div class="flex flex-wrap items-center gap-1.5 text-xs font-medium">
                            <span class="rounded-full px-2.5 py-0.5
                                {{ $product->status === 'active' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-100'
                                    : ($product->status === 'inactive' ? 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-100'
                                    : 'bg-slate-50 text-slate-600 ring-1 ring-inset ring-slate-100') }}">
                                {{ ucfirst($product->status) }}
                            </span>

                            <span class="rounded-full bg-slate-50 px-2.5 py-0.5 text-slate-600 ring-1 ring-inset ring-slate-100">
                                {{ ucfirst($product->type ?? 'Standard') }}
                            </span>
                        </div>
                    </div>

                    {{-- Price + Actions --}}
                    <div class="mt-3 flex flex-col items-end gap-2 sm:mt-0 sm:flex-row sm:items-center sm:gap-3">
                        <p class="text-sm font-semibold text-slate-900 whitespace-nowrap">
                            <x-money-display :amount="$product->price" :currency="auth()->user()->currency"/>
                        </p>

                        <div class="flex gap-1.5">
                            <a href="#" data-hotel-id="{{ $hotel->id }}" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}"
                               class="launchProductDeleteModal inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1 text-xs font-medium text-rose-700 transition hover:border-rose-300 hover:bg-rose-100">
                                <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                Delete
                            </a>

                            <a href="/admin/hotel/{{ $hotel->id }}/product/{{ $product->id }}/edit"
                               class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-200 bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 transition hover:border-indigo-300 hover:bg-indigo-100">
                                <i data-lucide="pen-square" class="h-3.5 w-3.5"></i>
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
@else
    <div class="flex flex-col items-center justify-center gap-4 rounded-3xl border border-dashed border-slate-300 bg-white/70 p-12 text-center shadow-sm">
        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-indigo-50 text-indigo-500">
            <i data-lucide="sparkles" class="h-6 w-6"></i>
        </div>
        <div class="space-y-2">
            <h3 class="text-xl font-semibold text-slate-900">Create your first product</h3>
            <p class="max-w-md text-sm text-slate-500">From local gems to in-house favourites — sell more with tailored product cards.</p>
        </div>
        <a href="/admin/hotel/{{ $hotel->id }}/product/create"
           class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100">
            <i data-lucide="plus" class="h-4 w-4"></i>
            Add your first product
        </a>
    </div>
@endif
