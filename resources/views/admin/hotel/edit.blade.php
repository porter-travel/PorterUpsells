<x-hotel-admin-layout :hotel="$hotel">
    <x-slot name="header">
        <div class="space-y-2">
            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500">Products & experiences</p>
            <h1 class="text-3xl font-semibold text-slate-900 sm:text-4xl">Add new products and experiences</h1>
            <p class="max-w-2xl text-sm text-slate-500">Build an on-brand menu of upsells for {{ $hotel->name }} that delights guests and drives incremental revenue.</p>
        </div>
    </x-slot>

    <div class="space-y-8">
        <section class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Products</h2>
                    <p class="text-sm text-slate-500">Reorder, update, or archive your products & experiences.</p>
                </div>
                <div x-data="{ open: false }" class="relative">
                    <button type="button" @click="open = !open" class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100">
                        <i data-lucide="plus" class="h-4 w-4"></i>
                        Add experience
                        <i data-lucide="chevron-down" class="h-4 w-4 transition" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-cloak x-show="open" @click.outside="open = false" x-transition class="absolute right-0 z-10 mt-2 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg">
                        <a href="{{ route('product.create', ['id' => $hotel->id, 'type' => 'standard']) }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 transition hover:bg-slate-50">
                            <i data-lucide="sparkles" class="h-4 w-4 text-indigo-500"></i>
                            Standard product
                        </a>
                        <a href="{{ route('product.create', ['id' => $hotel->id, 'type' => 'calendar']) }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 transition hover:bg-slate-50">
                            <i data-lucide="calendar" class="h-4 w-4 text-indigo-500"></i>
                            Calendar product
                        </a>
                        @if($hotel->property_type == 'hotel')
                            <span class="flex items-center gap-3 px-4 py-3 text-sm text-slate-400">
                                <i data-lucide="utensils" class="h-4 w-4"></i>
                                Restaurant booking <span class="ml-auto rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-widest">Soon</span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-8">
                @include('admin.hotel.partials.product-listings')
            </div>
        </section>
    </div>

    <div class="productDeleteModalContainer hidden fixed inset-0 z-50 bg-slate-900/70 backdrop-blur">
        <div class="fixed left-1/2 top-1/2 w-full max-w-md -translate-x-1/2 -translate-y-1/2 rounded-3xl border border-slate-200 bg-white p-8 shadow-2xl">
            <form method="post" action="">
                @csrf
                <div class="space-y-4 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                        <i data-lucide="alert-triangle" class="h-6 w-6"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-slate-900">Delete product</h4>
                    <p class="text-sm text-slate-500">Are you sure you want to remove <span id="productToDeleteName" class="font-semibold text-slate-900"></span>? This action cannot be undone.</p>
                </div>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
                    <x-secondary-button dusk="cancel-delete-product" type="button" class="justify-center">Cancel</x-secondary-button>
                    <x-danger-button dusk="confirm-delete-product" class="justify-center">Delete</x-danger-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const list = document.querySelector('.sortable-list');
            const deleteLinks = document.querySelectorAll('.launchProductDeleteModal');
            const modal = document.querySelector('.productDeleteModalContainer');
            const productNameSpan = document.getElementById('productToDeleteName');
            const form = modal.querySelector('form');
            const cancelButton = modal.querySelector("[dusk='cancel-delete-product']");
            let draggingItem = null;

            if (list) {
                list.addEventListener('dragstart', (e) => {
                    const handle = e.target.closest('.handle');
                    if (!handle) {
                        e.preventDefault();
                        return;
                    }
                    draggingItem = handle.closest('.sortable-item');
                    draggingItem?.classList.add('ring-2', 'ring-indigo-200', 'bg-indigo-50/60');
                });

                list.addEventListener('dragend', () => {
                    if (draggingItem) {
                        draggingItem.classList.remove('ring-2', 'ring-indigo-200', 'bg-indigo-50/60');
                        draggingItem = null;
                    }

                    document.querySelectorAll('.sortable-item').forEach(item => item.classList.remove('ring-2', 'ring-indigo-200', 'bg-indigo-50/60'));

                    const orderedProductIds = [...document.querySelectorAll('.sortable-item')]
                        .map(item => item.dataset.productId);

                    fetch('/admin/product/update-product-order', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ product_ids: orderedProductIds, hotel_id: {{ $hotel->id }} })
                    })
                        .then(response => response.json())
                        .catch(() => {});
                });

                list.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    const draggingOverItem = getDragAfterElement(list, e.clientY);
                    document.querySelectorAll('.sortable-item').forEach(item => item.classList.remove('ring-2', 'ring-indigo-200', 'bg-indigo-50/60'));

                    if (draggingOverItem) {
                        draggingOverItem.classList.add('ring-2', 'ring-indigo-200', 'bg-indigo-50/60');
                        list.insertBefore(draggingItem, draggingOverItem);
                    } else if (draggingItem) {
                        list.appendChild(draggingItem);
                    }
                });

                function getDragAfterElement(container, y) {
                    const draggableElements = [...container.querySelectorAll('.sortable-item:not(.dragging)')];
                    return draggableElements.reduce((closest, child) => {
                        const box = child.getBoundingClientRect();
                        const offset = y - box.top - box.height / 2;
                        if (offset < 0 && offset > closest.offset) {
                            return { offset, element: child };
                        } else {
                            return closest;
                        }
                    }, { offset: Number.NEGATIVE_INFINITY }).element;
                }
            }

            deleteLinks.forEach(link => {
                link.addEventListener('click', (event) => {
                    event.preventDefault();
                    const hotelId = link.getAttribute('data-hotel-id');
                    const productId = link.getAttribute('data-product-id');
                    const productName = link.getAttribute('data-product-name');

                    modal.classList.remove('hidden');
                    productNameSpan.textContent = productName;
                    form.action = `/admin/hotel/${hotelId}/product/${productId}/delete`;
                });
            });

            cancelButton?.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });
    </script>
</x-hotel-admin-layout>
