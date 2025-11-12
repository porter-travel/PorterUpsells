@php
    $selectedEmailSchedules = collect(old('send_email', ['now', '30', '7', '2']))
        ->map(fn ($value) => (string) $value)
        ->all();
@endphp

<x-hotel-admin-layout :hotel="$hotel">
    <x-slot name="header">

        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-3">
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500">Guests</p>
                <h1 class="text-3xl font-semibold text-slate-900 sm:text-4xl">Add a booking manually</h1>
                <p class="max-w-2xl text-sm text-slate-500">
                    Create a record for an upcoming stay so you can personalise the pre-arrival journey at {{ $hotel->name }}.
                </p>
            </div>
            <div class="grid w-full max-w-sm gap-4 rounded-3xl border border-slate-200 bg-white/90 p-5 shadow-sm backdrop-blur">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Property</p>
                    <p class="mt-1 text-base font-semibold text-slate-900">{{ $hotel->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Automations</p>
                    <p class="mt-1 inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-600">
                        <i data-lucide="mail-check" class="h-4 w-4"></i>
                        Emails scheduled
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <section class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
            <div class="space-y-6">
                <div class="space-y-2">
                    <h2 class="text-xl font-semibold text-slate-900">Guest details</h2>
                    <p class="text-sm text-slate-500">Capture the essentials we need to tailor their pre-arrival experience.</p>
                </div>

                <form enctype="multipart/form-data" method="post" action="{{ route('booking.store', $hotel->id) }}" class="space-y-8">
                    @csrf

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div class="space-y-2 sm:col-span-2">
                            <label for="guest_name" class="text-sm font-semibold text-slate-700">Guest name</label>
                            <x-text-input
                                id="guest_name"
                                name="guest_name"
                                type="text"
                                :value="old('guest_name')"
                                required
                                placeholder="Add the guest's full name"
                                class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <x-input-error :messages="$errors->get('guest_name')" class="text-sm text-rose-500" />
                        </div>

                        <div class="space-y-2">
                            <label for="arrival-date" class="text-sm font-semibold text-slate-700">Arrival date</label>
                            <x-text-input
                                id="arrival-date"
                                name="arrival_date"
                                type="date"
                                :value="old('arrival_date')"
                                required
                                class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <x-input-error :messages="$errors->get('arrival_date')" class="text-sm text-rose-500" />
                        </div>

                        <div class="space-y-2">
                            <label for="departure-date" class="text-sm font-semibold text-slate-700">Departure date</label>
                            <x-text-input
                                id="departure-date"
                                name="departure_date"
                                type="date"
                                :value="old('departure_date')"
                                required
                                class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <x-input-error :messages="$errors->get('departure_date')" class="text-sm text-rose-500" />
                        </div>

                        <div class="space-y-2 sm:col-span-2">
                            <label for="email-address" class="text-sm font-semibold text-slate-700">Email address</label>
                            <x-text-input
                                id="email-address"
                                name="email_address"
                                type="email"
                                :value="old('email_address')"
                                required
                                placeholder="guest@example.com"
                                class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <x-input-error :messages="$errors->get('email_address')" class="text-sm text-rose-500" />
                        </div>

                        <div class="space-y-2 sm:col-span-2">
                            <label for="booking-ref" class="text-sm font-semibold text-slate-700">Booking reference <span class="font-normal text-slate-400">(optional)</span></label>
                            <x-text-input
                                id="booking-ref"
                                name="booking_ref"
                                type="text"
                                :value="old('booking_ref')"
                                placeholder="Add a reference to help link this stay"
                                class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <x-input-error :messages="$errors->get('booking_ref')" class="text-sm text-rose-500" />
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-2">
                            <h3 class="text-lg font-semibold text-slate-900">Pre-arrival emails</h3>
                            <p class="text-sm text-slate-500">Choose when the welcome journey is sent. We'll handle the scheduling automatically.</p>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600 shadow-sm transition hover:border-indigo-200 hover:bg-indigo-50/60">
                                <input
                                    type="checkbox"
                                    name="send_email[]"
                                    value="now"
                                    @checked(in_array('now', $selectedEmailSchedules))
                                    class="mt-1 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                >
                                <span class="flex flex-col">
                                    <span class="font-semibold text-slate-900">Send immediately</span>
                                    <span class="text-xs text-slate-500">Share the welcome guide as soon as the booking is saved.</span>
                                </span>
                            </label>
                            <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600 shadow-sm transition hover:border-indigo-200 hover:bg-indigo-50/60">
                                <input
                                    type="checkbox"
                                    name="send_email[]"
                                    value="30"
                                    @checked(in_array('30', $selectedEmailSchedules))
                                    class="mt-1 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                >
                                <span class="flex flex-col">
                                    <span class="font-semibold text-slate-900">30 days before arrival</span>
                                    <span class="text-xs text-slate-500">Keep long-lead guests excited about their stay.</span>
                                </span>
                            </label>
                            <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600 shadow-sm transition hover:border-indigo-200 hover:bg-indigo-50/60">
                                <input
                                    type="checkbox"
                                    name="send_email[]"
                                    value="7"
                                    @checked(in_array('7', $selectedEmailSchedules))
                                    class="mt-1 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                >
                                <span class="flex flex-col">
                                    <span class="font-semibold text-slate-900">7 days before arrival</span>
                                    <span class="text-xs text-slate-500">Remind guests to plan add-ons once plans are confirmed.</span>
                                </span>
                            </label>
                            <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600 shadow-sm transition hover:border-indigo-200 hover:bg-indigo-50/60">
                                <input
                                    type="checkbox"
                                    name="send_email[]"
                                    value="2"
                                    @checked(in_array('2', $selectedEmailSchedules))
                                    class="mt-1 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                >
                                <span class="flex flex-col">
                                    <span class="font-semibold text-slate-900">2 days before arrival</span>
                                    <span class="text-xs text-slate-500">Prompt last-minute upgrades right before check-in.</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <x-primary-button class="w-full justify-center text-base">Store booking</x-primary-button>
                </form>
            </div>
        </section>
    </div>
</x-hotel-admin-layout>
