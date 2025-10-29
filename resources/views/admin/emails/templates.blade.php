<x-hotel-admin-layout :hotel="$hotel">
    <x-slot name="header">
        <div class="space-y-2">
            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500">Email automations</p>
            <h1 class="text-3xl font-semibold text-slate-900 sm:text-4xl">Add new email automations</h1>
            <p class="max-w-2xl text-sm text-slate-500">Build email automations for  {{ $hotel->name }} that
                will help inform your guests and drive revenue before, during and after their stay.</p>
        </div>
    </x-slot>

    <div class="space-y-8">
        <section class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Email Automations</h2>
                    <p class="text-sm text-slate-500">Create, update or delete your email automations</p>
                </div>
                <a href="{{route('email.builder', ['hotel_id' => $hotel->id])}}"
                   class="">
                    <x-primary-button>
                        Add New Automation
                    </x-primary-button>
                </a>
            </div>

            <div class="mt-8">

                @if(count($email_templates) > 0)
                    <div class="space-y-3 sm:space-y-4">
                        @foreach($email_templates as $template)
                            {{-- Item Container: Matches the new product card style --}}
                            <div data-template-id="{{ $template->id }}"
                                 class="group flex items-center gap-4 rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                                {{-- Drag handle (for assumed sortability, matching new style) --}}
                                {{--                                    <div draggable="true"--}}
                                {{--                                         class="handle flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-400 transition group-hover:border-indigo-200 group-hover:text-indigo-500 cursor-grab active:cursor-grabbing">--}}
                                {{--                                        <i data-lucide="grip-vertical" class="h-5 w-5"></i>--}}
                                {{--                                    </div>--}}

                                {{-- Automation Icon (Replaces the image area with a visual identifier) --}}
                                <div
                                    class="flex h-20 w-24 shrink-0 items-center justify-center rounded-xl bg-indigo-50/70 text-indigo-600 border border-indigo-200">
                                    <i data-lucide="mail" class="h-8 w-8"></i>
                                </div>

                                {{-- Main info --}}
                                <div
                                    class="flex flex-1 flex-col justify-center sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                                    <div class="flex-1 space-y-1.5">
                                        {{-- Automation Name --}}
                                        <a href="{{ route('email.builder.edit', ['hotel_id' => $hotel->id, 'template_id' => $template->id]) }}"
                                           class="hover:underline">
                                            <p class="text-base font-semibold text-slate-900">{{ $template->name }}</p>
                                        </a>

                                        {{-- Details / Tags (When to Send, Days Offset) --}}
                                        <div class="flex flex-wrap items-center gap-1.5 text-xs font-medium">
                                            @php
                                                $whenToSend = $keys[$template->when_to_send] ?? 'Unknown';

                                                // Dynamic badge coloring based on the 'when_to_send' value
                                                $badgeClass = match(strtolower($whenToSend)) {
                                                    'after stay' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-100',
                                                    'before stay' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-100',
                                                    'upon booking' => 'bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-100',
                                                    default => 'bg-slate-50 text-slate-600 ring-1 ring-inset ring-slate-100',
                                                };
                                            @endphp

                                            {{-- When to Send Badge --}}
                                            <span class="rounded-full px-2.5 py-0.5 {{ $badgeClass }}">
                                {{ ucfirst($whenToSend) }}
                            </span>

                                            {{-- Days Offset Badge --}}
                                            <span
                                                class="rounded-full bg-slate-50 px-2.5 py-0.5 text-slate-600 ring-1 ring-inset ring-slate-100">
                                {{ $template->days }} {{ $template->days > 1 ? 'Days' : 'Day' }} Offset
                            </span>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div
                                        class="mt-3 flex flex-col items-end gap-2 sm:mt-0 sm:flex-row sm:items-center sm:gap-3">
                                        <div class="flex gap-1.5">
                                            {{-- Delete Button (Using new style with trash icon and specific data attributes) --}}
                                            {{-- Assuming a route for creation exists, otherwise, replace route with a suitable placeholder --}}
                                            <a href="{{ route('email.builder.destroy', ['hotel_id' => $hotel->id, 'template_id' => $template->id]) }}"
                                               data-hotel-id="{{ $hotel->id }}" data-template-id="{{ $template->id }}"
                                               data-template-name="{{ $template->name }}"
                                               class="launchAutomationDeleteModal inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1 text-xs font-medium text-rose-700 transition hover:border-rose-300 hover:bg-rose-100">
                                                <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                                Delete
                                            </a>

                                            {{-- Edit Button (Using new style with pen icon) --}}
                                            <a href="{{ route('email.builder.edit', ['hotel_id' => $hotel->id, 'template_id' => $template->id]) }}"
                                               class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-200 bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 transition hover:border-indigo-300 hover:bg-indigo-100">
                                                <i data-lucide="pen-square" class="h-3.5 w-3.5"></i>
                                                Edit
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Empty State (Mimics the new style's placeholder) --}}
                    <div
                        class="flex flex-col items-center justify-center gap-4 rounded-3xl border border-dashed border-slate-300 bg-white/70 p-12 text-center shadow-sm">
                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-full bg-indigo-50 text-indigo-500">
                            <i data-lucide="mail-check" class="h-6 w-6"></i>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-xl font-semibold text-slate-900">No Email Automations Found</h3>
                            <p class="max-w-md text-sm text-slate-500">Create new email automations to communicate with
                                your guests at key moments, such as before or after their stay.</p>
                        </div>
                        {{-- Assuming a route for creating a new automation exists --}}
                        <a href="{{ route('email.builder', ['hotel_id' => $hotel->id]) }}"
                           class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100">
                            <i data-lucide="plus" class="h-4 w-4"></i>
                            Create a New Automation
                        </a>
                    </div>
                @endif


            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const deleteLinks = document.querySelectorAll('.launchAutomationDeleteModal');
            const modal = document.querySelector('.productDeleteModalContainer');
            const productNameSpan = document.getElementById('productToDeleteName');
            const form = modal.querySelector('form');
            const cancelButton = modal.querySelector("[dusk='cancel-delete-product']");

            deleteLinks.forEach(link => {
                link.addEventListener('click', (event) => {
                    event.preventDefault();
                    const hotelId = link.getAttribute('data-hotel-id');
                    const templateId = link.getAttribute('data-template-id');
                    const templateName = link.getAttribute('data-template-name');

                    modal.classList.remove('hidden');
                    productNameSpan.textContent = templateName;
                    form.action = `/admin/hotel/${hotelId}/email_builder/${templateId}/delete`;
                });
            });

            cancelButton?.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });
    </script>

    <x-slot name="modals">
        <div class="productDeleteModalContainer hidden fixed inset-0 z-50 bg-slate-900/70 backdrop-blur !my-0">
            <div class="fixed left-1/2 top-1/2 w-full max-w-md -translate-x-1/2 -translate-y-1/2 rounded-3xl border border-slate-200 bg-white p-8 shadow-2xl">
                <form method="post" action="">
                    @csrf
                    <div class="space-y-4 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                            <i data-lucide="alert-triangle" class="h-6 w-6"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-900">Delete product</h4>
                        <p class="text-sm text-slate-500">Are you sure you want to remove <span id="productToDeleteName" class="font-semibold text-slate-900"></span>? This action cannot be undone.  It will also delete all scheduled emails that use this automation.</p>
                    </div>
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
                        <x-secondary-button dusk="cancel-delete-product" type="button" class="justify-center">Cancel</x-secondary-button>
                        <x-danger-button dusk="confirm-delete-product" class="justify-center">Delete</x-danger-button>
                    </div>
                </form>
            </div>
        </div>
    </x-slot>
</x-hotel-admin-layout>
