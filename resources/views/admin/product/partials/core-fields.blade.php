@if($errors->any())
    <div class="rounded-2xl border border-rose-200 bg-rose-50/80 p-4 text-sm text-rose-700">
        <p class="font-semibold">We spotted {{ $errors->count() }} issue{{ $errors->count() > 1 ? 's' : '' }} with your details:</p>
        <ul class="mt-2 list-disc space-y-1 pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid gap-6 lg:grid-cols-[1fr_auto]">
    <div class="space-y-3">
        <x-input-label class="text-slate-700" for="image" :value="__('Product image')" />
        <div class="flex flex-wrap items-center gap-4">
            @if($product->image)
                <img src="{{ $product->image }}" alt="product" class="h-16 w-16 rounded-2xl object-cover ring-2 ring-slate-200" />
            @endif
            <input @if($method === 'create') required @endif type="file" name="image" id="image" class="text-sm text-slate-600" accept="image/*">
        </div>
        <p class="text-xs text-slate-500">Use a 500 × 500px image smaller than 1MB for the best guest experience.</p>
    </div>
    <div class="flex flex-col items-start gap-2 self-end">
        <x-input-label class="text-slate-700" for="status" :value="__('Status')" />
        <select name="status" id="status" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="active" @selected($product->status === 'active')>Active</option>
            <option value="inactive" @selected($product->status === 'inactive')>Inactive</option>
            <option value="draft" @selected($product->status === 'draft')>Draft</option>
        </select>
    </div>
</div>

<div class="grid gap-6 md:grid-cols-2">
    <div class="space-y-3">
        <x-input-label class="text-slate-700" for="name" value="Name" />
        <x-text-input id="name" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm" type="text" name="name" :value="$product->name" required placeholder="Signature welcome amenity" />
        <x-input-error :messages="$errors->get('name')" class="text-xs text-rose-600" />
    </div>
    <div class="space-y-3">
        <x-input-label class="text-slate-700" for="price" :value="__('Price')" />
        <x-text-input id="price" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm" type="number" name="price" :value="$product->price" required step=".01" placeholder="12.34" />
        <x-input-error :messages="$errors->get('price')" class="text-xs text-rose-600" />
    </div>
</div>

<div class="space-y-3">
    <div class="flex items-center justify-between">
        <x-input-label class="text-slate-700" :value="__('Description')" />
    </div>
    <div id="description" class="quill-text-editor min-h-[180px] rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
        {!! $product->description !!}
    </div>
    <input id="realDescription" type="hidden" name="description" value="{{ $product->description }}" required>
    <x-input-error :messages="$errors->get('description')" class="text-xs text-rose-600" />
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quill = new Quill('#description', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['clean']
                ]
            }
        });

        quill.on('text-change', () => {
            const hiddenDescription = document.getElementById('realDescription');
            if (hiddenDescription) {
                hiddenDescription.value = quill.root.innerHTML;
            }
        });

        const rewriteButton = document.getElementById('rewriteProductDescription');
        if (rewriteButton) {
            rewriteButton.addEventListener('click', function () {
                const description = document.getElementById('realDescription')?.value ?? '';

                fetch('/admin/chat/rewrite-product-descriptions', {
                    method: 'POST',
                    body: JSON.stringify({ 'product_description': description }),
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json, text-plain, */*',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name=_token]')?.value ?? ''
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        quill.root.innerHTML = data.result ?? '';
                        const hiddenInput = document.getElementById('realDescription');
                        if (hiddenInput) {
                            hiddenInput.value = data.result ?? '';
                        }
                    })
                    .catch(() => {});
            });
        }
    });
</script>
