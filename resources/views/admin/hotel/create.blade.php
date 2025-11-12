<x-hotel-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Property') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-200 bg-gradient-to-r from-white via-indigo-50 to-white px-6 py-8 shadow-sm sm:px-10">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-widest text-slate-500">New property</p>
                        <h1 class="mt-2 text-3xl font-semibold text-slate-900">Let's set up your next standout stay</h1>
                        <p class="mt-3 max-w-2xl text-sm text-slate-500">
                            Tell us a few essentials about your property and upload imagery so guests can get a feel for
                            the experience. You can refine everything once the property is created.
                        </p>
                    </div>
                    <div class="flex w-full max-w-xs items-center gap-3 rounded-2xl bg-slate-900/5 p-4 text-sm text-slate-600 md:w-auto">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-r from-indigo-600 via-purple-600 to-violet-600 text-white shadow-lg shadow-indigo-500/30">1</div>
                        <div>
                            <p class="font-semibold text-slate-800">Step 1 of 3</p>
                            <p class="text-xs">Core property information</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <form enctype="multipart/form-data" method="post" action="/admin/hotel/create" class="flex flex-col gap-8 p-6 sm:p-10">
                        @csrf

                        <section class="space-y-6">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                                    <i data-lucide="building-2" class="h-5 w-5"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-slate-900">Property details</h2>
                                    <p class="text-sm text-slate-500">Provide the essentials that will help guests recognise your brand.</p>
                                </div>
                            </div>

                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <x-input-label class="font-medium text-slate-700" for="name" :value="__('Property name')" />
                                    <x-text-input dusk="hotel-name" id="name" class="block w-full" type="text" name="name" :value="old('name')" required placeholder="E.g. Azure Coast Resort" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                                </div>
                                <div class="space-y-2">
                                    <x-input-label class="font-medium text-slate-700" for="property-type" :value="__('Property type')" />
                                    <input type="hidden" name="property_type" value="hotel">
                                    <div class="relative">
                                        <select disabled name="property_type" id="property-type" class="block w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-slate-500">
                                            <option value="hotel">Hotel</option>
                                            <option value="restaurant">Restaurant</option>
                                        </select>
                                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-400">Additional property types are coming soon.</p>
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <x-input-label class="font-medium text-slate-700" for="address" :value="__('Property address')" />
                                    <x-text-input dusk="hotel-address" id="address" class="block w-full" type="text" name="address" :value="old('address')" required placeholder="123 Ocean View Blvd, Miami" />
                                    <x-input-error :messages="$errors->get('address')" class="mt-1" />
                                </div>
                            </div>
                        </section>

                        <section class="space-y-6">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-50 text-sky-600">
                                    <i data-lucide="images" class="h-5 w-5"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-slate-900">Imagery</h2>
                                    <p class="text-sm text-slate-500">Upload assets that best represent your property and listing.</p>
                                </div>
                            </div>

                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="space-y-3">
                                    <x-input-label class="font-medium text-slate-700" for="logo" :value="__('Brand logo')" />
                                    <label for="logo" class="group relative flex min-h-[220px] flex-col items-center justify-center gap-2 rounded-2xl border border-dashed border-slate-300 bg-slate-50/80 px-6 py-8 text-center transition hover:border-slate-400 hover:bg-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-10 w-10 text-slate-400 transition group-hover:text-slate-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V3.75m0 12.75l3-3m-3 3l-3-3" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12.75v6a1.5 1.5 0 001.5 1.5h15a1.5 1.5 0 001.5-1.5v-6" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-700">Drop your logo here</p>
                                            <p class="text-xs text-slate-400">SVG or PNG, max 2MB</p>
                                        </div>
                                        <input dusk="hotel-logo" required type="file" name="logo" id="logo" class="absolute inset-0 h-full w-full cursor-pointer opacity-0" />
                                    </label>
                                    <x-input-error :messages="$errors->get('logo')" class="mt-1" />
                                </div>
                                <div class="space-y-3">
                                    <x-input-label class="font-medium text-slate-700" for="featured_image" :value="__('Featured image')" />
                                    <label for="featured_image" class="group relative flex min-h-[220px] flex-col items-center justify-center gap-2 rounded-2xl border border-dashed border-slate-300 bg-slate-50/80 px-6 py-8 text-center transition hover:border-slate-400 hover:bg-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-10 w-10 text-slate-400 transition group-hover:text-slate-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5v13.5H3.75z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l3.75-3.75a1.5 1.5 0 012.12 0l5.13 5.13" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9.75a1.5 1.5 0 103 0 1.5 1.5 0 00-3 0z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-700">Upload your hero image</p>
                                            <p class="text-xs text-slate-400">High-resolution JPG, PNG or WEBP</p>
                                        </div>
                                        <input type="file" name="featured_image" id="featured_image" class="absolute inset-0 h-full w-full cursor-pointer opacity-0" />
                                    </label>
                                    <x-input-error :messages="$errors->get('featured_image')" class="mt-1" />
                                </div>
                            </div>
                        </section>

                        <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-sm text-slate-500">You can always edit your property information later from the admin dashboard.</p>
                            <x-primary-button dusk="create-hotel" class="inline-flex items-center justify-center gap-2 px-6 py-2 text-base">
                                Save &amp; continue
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 11-1.414-1.414L13.586 11H4a1 1 0 110-2h9.586l-3.293-3.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <aside class="flex flex-col gap-6 rounded-3xl border border-transparent bg-gradient-to-br from-indigo-800 via-purple-800 to-violet-900 px-6 py-8 text-slate-100 shadow-xl shadow-indigo-900/30">
                    <div class="space-y-3">
                        <p class="text-sm font-semibold uppercase tracking-widest text-indigo-200/80">Success checklist</p>
                        <h2 class="text-2xl font-semibold text-indigo-200">Tips for a compelling listing</h2>
                        <p class="text-sm text-indigo-100/80">Stand out in search results by showcasing what makes your property unique. Here's what our top-performing partners do.</p>
                    </div>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start gap-4">
                            <span class="mt-1 flex h-7 w-7 items-center justify-center rounded-full bg-white/15 text-indigo-100 shadow-inner shadow-indigo-900/40">
                                <i data-lucide="check" class="h-4 w-4"></i>
                            </span>
                            <div class="space-y-1">
                                <p class="font-medium text-white">Use a clear, descriptive name</p>
                                <p class="text-indigo-100/70">Highlight neighbourhood, property style or signature amenities.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="mt-1 flex h-7 w-7 items-center justify-center rounded-full bg-white/15 text-indigo-100 shadow-inner shadow-indigo-900/40">
                                <i data-lucide="map-pin" class="h-4 w-4"></i>
                            </span>
                            <div class="space-y-1">
                                <p class="font-medium text-white">Pinpoint the address</p>
                                <p class="text-indigo-100/70">Accurate location details make it easy for guests to find you and boost trust.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="mt-1 flex h-7 w-7 items-center justify-center rounded-full bg-white/15 text-indigo-100 shadow-inner shadow-indigo-900/40">
                                <i data-lucide="camera" class="h-4 w-4"></i>
                            </span>
                            <div class="space-y-1">
                                <p class="font-medium text-white">Show off the space</p>
                                <p class="text-indigo-100/70">Upload bright, high-resolution imagery that captures your best angles.</p>
                            </div>
                        </li>
                    </ul>
                    <div class="rounded-2xl bg-white/10 p-4 text-sm text-indigo-100/80">
                        <p class="font-medium text-white">Need help?</p>
                        <p>Our onboarding team is available 24/7 at <a href="mailto:onboarding@ems.com" class="font-semibold text-white underline decoration-white/40 decoration-2 underline-offset-4">onboarding@ems.com</a>.</p>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-hotel-admin-layout>
