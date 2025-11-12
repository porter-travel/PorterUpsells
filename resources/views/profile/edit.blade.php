@php
    $activeHotel = auth()->user()?->hotels->first();
@endphp

<x-hotel-admin-layout :hotel="$activeHotel">
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-2">
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500">Account settings</p>
                <h1 class="text-3xl font-semibold text-slate-900 sm:text-4xl">Manage your profile & billing</h1>
                <p class="max-w-2xl text-sm text-slate-500">Keep your contact details up to date, manage payout connections, and secure your Enhance My Stay account.</p>
            </div>
            <div class="rounded-3xl border border-indigo-100 bg-indigo-50/70 px-5 py-4 text-sm text-indigo-600 shadow-sm">
                <p class="font-semibold">{{ auth()->user()->name }}</p>
                <p>{{ auth()->user()->email }}</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        @if(!$user->stripe_account_active)
            <div class="rounded-3xl border border-amber-200 bg-gradient-to-br from-amber-50 via-white to-white p-6 shadow-sm sm:p-8">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                        <i data-lucide="badge-alert" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Activate payouts</h2>
                        <p class="text-sm text-slate-600">Connect your Stripe account so guest payments can flow seamlessly.</p>
                    </div>
                </div>
                <div class="mt-6 max-w-xl">
                    @include('profile.partials.create-connected-account-form')
                </div>
            </div>
        @endif

        <div class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-50 text-indigo-500">
                    <i data-lucide="user" class="h-5 w-5"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Profile information</h2>
                    <p class="text-sm text-slate-500">Update your name, email, and contact preferences.</p>
                </div>
            </div>
            <div class="max-w-xl space-y-6">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        @if(isset($hotels) && $hotels->count())
            <div class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
                <div class="mb-6 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-500">
                        <i data-lucide="plug" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Integrations</h2>
                        <p class="text-sm text-slate-500">Manage API keys and connected services for each property.</p>
                    </div>
                </div>
                <div class="space-y-8">
                    @foreach($hotels as $hotel)
                        <div class="rounded-2xl border border-slate-200 bg-white/90 p-6 shadow-inner shadow-slate-200/40">
                            @include('profile.partials.update-hotel-integrations-form', ['hotel' => $hotel])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-50 text-slate-500">
                    <i data-lucide="lock" class="h-5 w-5"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Security</h2>
                    <p class="text-sm text-slate-500">Choose a strong password to protect your account.</p>
                </div>
            </div>
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-white">
                    <i data-lucide="credit-card" class="h-5 w-5"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Subscription</h2>
                    <p class="text-sm text-slate-500">Access billing history, upgrade plans, and manage invoices.</p>
                </div>
            </div>
            <a href="https://billing.stripe.com/p/login/cN2aF2996gkpbGEdQQ" class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100">
                <i data-lucide="external-link" class="h-4 w-4"></i>
                Manage subscription
            </a>
        </div>

        <div class="rounded-3xl border border-rose-200 bg-rose-50/70 p-6 shadow-sm sm:p-8">
            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                    <i data-lucide="shield-off" class="h-5 w-5"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-rose-700">Delete account</h2>
                    <p class="text-sm text-rose-600">This will permanently remove your account and all related data.</p>
                </div>
            </div>
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-hotel-admin-layout>
