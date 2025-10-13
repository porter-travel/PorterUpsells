@if(count($products) > 0)
    <ul class="sortable-list space-y-4">
        @foreach($products as $key => $product)
            <li data-product-id="{{ $product->id }}" class="sortable-item group flex w-full items-stretch gap-3 rounded-3xl border border-slate-200 bg-white/90 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                <div draggable="true" class="handle flex items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 px-3 text-slate-400 transition group-hover:border-indigo-200 group-hover:text-indigo-500">
                    <i data-lucide="grip-vertical" class="h-6 w-6"></i>
                </div>
                <div class="flex flex-grow flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <a class="flex flex-1 items-center gap-4" href="/admin/hotel/{{ $hotel->id }}/product/{{ $product->id }}/edit">
                        <div class="h-20 w-24 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                            @include('hotel.partials.product-image', ['item' => $product])
                        </div>
                        <div class="space-y-2">
                            <p class="text-base font-semibold text-slate-900">{{ $product->name }}</p>
                            <div class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-widest">
                                <span class="rounded-full px-3 py-1 {{ $product->status === 'active' ? 'bg-emerald-100 text-emerald-600' : ($product->status === 'inactive' ? 'bg-rose-100 text-rose-600' : 'bg-slate-100 text-slate-500') }}">{{ ucfirst($product->status) }}</span>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-500">{{ ucfirst($product->type ?? 'standard') }}</span>
                            </div>
                        </div>
                    </a>
                    <div class="flex flex-col items-end gap-3 sm:items-end">
                        <p class="text-lg font-semibold text-slate-900">
                            <x-money-display :amount="$product->price" :currency="auth()->user()->currency"/>
                        </p>
                        <div class="flex items-center gap-2">
                            <a class="launchProductDeleteModal inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-1.5 text-sm font-semibold text-rose-600 transition hover:border-rose-300 hover:bg-rose-100" data-hotel-id="{{ $hotel->id }}" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" href="#">
                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                Delete
                            </a>
                            <a href="/admin/hotel/{{ $hotel->id }}/product/{{ $product->id }}/edit" class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-sm font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100">
                                <i data-lucide="pen-square" class="h-4 w-4"></i>
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
            <h3 class="text-xl font-semibold text-slate-900">Create your first experience</h3>
            <p class="max-w-md text-sm text-slate-500">Bring your signature amenities and local partnerships to life with tailored product cards.</p>
        </div>
        <a href="/admin/hotel/{{ $hotel->id }}/product/create" class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100">
            <i data-lucide="plus" class="h-4 w-4"></i>
            Add your first product
        </a>
    </div>
@endif
