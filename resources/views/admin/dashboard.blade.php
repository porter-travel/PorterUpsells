@php
    $hotelsCollection = collect($hotels);
    $primaryHotel = $hotelsCollection->first();
@endphp

<x-hotel-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-slate-500">Welcome back</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-900 sm:text-4xl">
                    {{ $user->name ?? 'Admin User' }}
                </h1>
                <p class="mt-3 max-w-2xl text-sm text-slate-500">
                    Manage your properties, track performance, and create seamless interactions — all from one powerful dashboard.
                </p>
            </div>
            <div class="grid w-full max-w-xl grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
    {{-- Properties --}}
    <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white/90 p-5 shadow-sm backdrop-blur transition hover:shadow-md">
        <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-400">
            Properties
            <i data-lucide="building-2" class="h-4 w-4 text-slate-300"></i>
        </div>
        <div class="mt-5 flex justify-between items-center">
                        <span class="inline-flex items-center rounded-full bg-slate-900/5 px-3 py-1 text-sm font-semibold text-slate-900">
                {{ $hotelsCollection->count() }}
            </span>
        </div>
    </div>

    {{-- Stripe Status --}}
    <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white/90 p-5 shadow-sm backdrop-blur transition hover:shadow-md">
        <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-400">
            Stripe status
            <i data-lucide="credit-card" class="h-4 w-4 text-slate-300"></i>
        </div>
        <div class="mt-5 flex justify-between items-center">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold
                {{ $user->stripe_account_active 
                    ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200' 
                    : 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-200' }}">
                {{ $user->stripe_account_active ? 'Active' : 'Pending' }}
            </span>
        </div>
    </div>

    {{-- Quick Action --}}
    <a href="{{ route('hotel.create') }}" class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-slate-200 bg-white/90 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-400">
            Quick action
            <i data-lucide="sparkles" class="h-4 w-4 text-indigo-400"></i>
        </div>
        <div class="mt-5 flex justify-between items-center">
                        <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-sm font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-200">
                Add property
                <i data-lucide="arrow-up-right" class="ml-1 h-4 w-4"></i>
            </span>
        </div>
        <div class="pointer-events-none absolute inset-0 -z-10 opacity-0 transition group-hover:opacity-100">
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-violet-500"></div>
        </div>
    </a>
</div>

        </div>
    </x-slot>

    <div class="space-y-8">
        @if($user->stripe_account_active)
            @if($hotelsCollection->isNotEmpty())
                <section class="space-y-6">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900 sm:text-2xl">Your properties</h2>
                            <p class="text-sm text-slate-500">Switch into a property to design on-brand experiences and manage fulfilment.</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('hotel.create') }}" class="inline-flex items-center gap-2 rounded-xl border border-transparent bg-gradient-to-r from-indigo-600 via-purple-600 to-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition hover:from-indigo-500 hover:via-purple-500 hover:to-violet-500">
                                <i data-lucide="plus" class="h-4 w-4"></i>
                                New property
                            </a>
                            <a href="{{ route('performance.index', ['hotel_id' => $primaryHotel?->id]) }}" class="inline-flex items-center gap-2 rounded-xl border border-transparent bg-white/90 px-4 py-2 text-sm font-semibold text-indigo-700 shadow-sm backdrop-blur transition hover:bg-white">
                                <i data-lucide="bar-chart-3" class="h-4 w-4"></i>
                                View performance
                            </a>
                        </div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($hotelsCollection as $hotel)
                            <article
                                class="group relative overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl"
                                role="link"
                                tabindex="0"
                                onclick="window.location.href='{{ route('hotel.edit', ['id' => $hotel->id]) }}'"
                                onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); window.location.href='{{ route('hotel.edit', ['id' => $hotel->id]) }}'; }"
                            >
                                <div class="absolute inset-0 bg-gradient-to-br from-slate-900/0 via-slate-900/5 to-slate-900/10 opacity-0 transition group-hover:opacity-100"></div>
                                <div class="aspect-[4/3] overflow-hidden">
                                    <img
                                        src="{{ $hotel->logo }}"
                                        alt="{{ $hotel->name }} logo"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                    />
                                </div>
                                <div class="relative space-y-4 p-6">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Property</p>
                                        <h3 class="mt-2 text-xl font-semibold text-slate-900">{{ $hotel->name }}</h3>
                                    </div>
                                    <dl class="grid grid-cols-2 gap-4 text-xs text-slate-500">
                                        <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-3">
                                            <dt class="flex items-center gap-2 font-semibold text-slate-600">
                                                <i data-lucide="shopping-bag" class="h-3.5 w-3.5"></i>
                                                Orders
                                            </dt>
                                            <dd class="mt-1 text-lg font-semibold text-slate-900">Live</dd>
                                        </div>
                                        <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-3">
                                            <dt class="flex items-center gap-2 font-semibold text-slate-600">
                                                <i data-lucide="calendar" class="h-3.5 w-3.5"></i>
                                                Calendar
                                            </dt>
                                            <dd class="mt-1 text-lg font-semibold text-slate-900">Synced</dd>
                                        </div>
                                    </dl>
                                    <div class="flex flex-wrap gap-2">

                                        <a href="{{ 'http://127.0.0.1:8080/admin/hotel/' . $hotel->id . '/branding' }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:border-slate-300 hover:text-slate-900" onclick="event.stopPropagation()">
                                            <i data-lucide="pen-square" class="h-4 w-4"></i>
                                            Edit brand
                                        </a>
                                        <a href="{{ 'http://127.0.0.1:8080/admin/hotel/' . $hotel->id . '/orders/v2' }}" class="inline-flex items-center gap-2 rounded-xl border border-transparent bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800" onclick="event.stopPropagation()">

                                            <i data-lucide="shopping-cart" class="h-4 w-4"></i>
                                            Manage orders
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @else
                <section class="rounded-3xl border border-transparent bg-gradient-to-br from-indigo-50 via-white to-white p-10 text-center shadow-sm">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-900/10">
                        <i data-lucide="building-2" class="h-8 w-8 text-indigo-600"></i>
                    </div>
                    <h2 class="mt-6 text-2xl font-semibold text-slate-900">Create your first property</h2>
                    <p class="mt-3 text-sm text-slate-500">Design your guest marketplace, set up fulfilment, and start accepting curated upsells in minutes.</p>
                    <div class="mt-6 flex flex-wrap justify-center gap-3">
                        <a href="{{ route('hotel.create') }}" class="inline-flex items-center gap-2 rounded-xl border border-transparent bg-gradient-to-r from-indigo-600 via-purple-600 to-violet-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:from-indigo-500 hover:via-purple-500 hover:to-violet-500">
                            <i data-lucide="sparkles" class="h-4 w-4"></i>
                            Create property
                        </a>
                        <a href="mailto:support@enhancemystay.com" class="inline-flex items-center gap-2 rounded-xl border border-transparent bg-white/90 px-5 py-2.5 text-sm font-semibold text-indigo-700 shadow-sm backdrop-blur transition hover:bg-white">
                            <i data-lucide="message-circle" class="h-4 w-4"></i>
                            Talk to us
                        </a>
                    </div>
                </section>
            @endif
        @else
            <section class="grid gap-8 lg:grid-cols-[1.2fr_1fr]">
                <div class="rounded-3xl border border-slate-200 bg-gradient-to-br from-amber-100 via-white to-white p-10 shadow-lg shadow-amber-200/40">
                    <h2 class="text-3xl font-semibold text-slate-900">Let's activate payouts</h2>
                    <p class="mt-4 text-sm text-slate-600">To start receiving guest payments, complete your Stripe onboarding with the required business information and payout details.</p>
                    <dl class="mt-8 grid gap-6 text-sm text-slate-600 sm:grid-cols-2">
                        <div class="rounded-2xl border border-white/60 bg-white/70 p-5 shadow-sm">
                            <dt class="flex items-center gap-2 font-semibold text-slate-700">
                                <i data-lucide="shield-check" class="h-4 w-4 text-amber-500"></i>
                                Secure verification
                            </dt>
                            <dd class="mt-2">Stripe securely verifies your business to protect both you and your guests.</dd>
                        </div>
                        <div class="rounded-2xl border border-white/60 bg-white/70 p-5 shadow-sm">
                            <dt class="flex items-center gap-2 font-semibold text-slate-700">
                                <i data-lucide="clock-9" class="h-4 w-4 text-amber-500"></i>
                                Fast payouts
                            </dt>
                            <dd class="mt-2">Receive funds on a daily 7-day rolling schedule once verification is complete.</dd>
                        </div>
                    </dl>
                    <a href="{{ route('profile.edit') }}" class="mt-8 inline-flex items-center gap-2 rounded-xl border border-transparent bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/25 hover:bg-slate-800">
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        Complete information
                    </a>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">What you'll need</h3>
                    <ul class="mt-6 space-y-4 text-sm text-slate-600">
                        <li class="flex items-start gap-3">
                            <span class="mt-1 flex h-7 w-7 items-center justify-center rounded-full bg-slate-900/10 text-slate-900">
                                <i data-lucide="briefcase" class="h-4 w-4"></i>
                            </span>
                            Registered business details and legal entity name
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 flex h-7 w-7 items-center justify-center rounded-full bg-slate-900/10 text-slate-900">
                                <i data-lucide="landmark" class="h-4 w-4"></i>
                            </span>
                            Proof of address and primary contact information
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 flex h-7 w-7 items-center justify-center rounded-full bg-slate-900/10 text-slate-900">
                                <i data-lucide="banknote" class="h-4 w-4"></i>
                            </span>
                            Payout bank account where you wish to receive guest payments
                        </li>
                    </ul>
                </div>
            </section>
        @endif
    </div>
</x-hotel-admin-layout>
