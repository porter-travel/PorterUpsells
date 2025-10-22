@props(['hotel' => null])

@php
    $user = auth()->user();
    $hotels = collect($user?->hotels ?? []);
    $currentHotelId = $hotel?->id;
    $activeHotel = $currentHotelId
        ? $hotels->firstWhere('id', $currentHotelId)
        : null;
    $currentRoute = request()->route();
    $currentRouteName = $currentRoute?->getName();
    $currentRouteParameters = collect($currentRoute?->parameters() ?? []);
    $hotelParameterKey = null;

    if ($currentHotelId) {
        $hotelParameterKey = $currentRouteParameters->search(function ($value) use ($currentHotelId) {
            return (string) $value === (string) $currentHotelId;
        });
    }

    $queryString = request()->getQueryString();

    $buildHotelSwitchUrl = function ($hotelId) use ($currentRouteName, $currentRouteParameters, $hotelParameterKey, $queryString) {
        if ($currentRouteName && $hotelParameterKey !== null && $hotelParameterKey !== false) {
            $parameters = $currentRouteParameters->toArray();
            $parameters[$hotelParameterKey] = $hotelId;
            $url = route($currentRouteName, $parameters);

            return $queryString ? $url . '?' . $queryString : $url;
        }

        return route('hotel.edit', ['id' => $hotelId]);
    };

    $navigation = [
        [
            'label' => 'Overview',
            'icon' => 'layout-dashboard',
            'href' => route('dashboard'),
            'active' => request()->routeIs('dashboard'),
        ],
        [
            'label' => 'Products & Experiences',
            'icon' => 'sparkles',
            'href' => $hotel ? route('hotel.edit', ['id' => $hotel->id]) : route('hotel.create'),
            'active' => request()->routeIs('hotel.edit') || request()->routeIs('product.*'),
            'disabled' => ! $hotel,
        ],
        [
            'label' => 'Branding',
            'icon' => 'paintbrush',
            'href' => $hotel ? route('hotel.branding', ['id' => $hotel->id]) : route('hotel.create'),
            'active' => request()->routeIs('hotel.branding'),
            'disabled' => ! $hotel,
        ],
        [
            'label' => 'Emails',
            'icon' => 'mail',
            'href' => $hotel ? route('email-v2.list-templates', ['hotel_id' => $hotel->id]) : route('hotel.create'),
            'active' => request()->routeIs('email.*'),
            'disabled' => ! $hotel,
        ],
        [
            'label' => 'Orders',
            'icon' => 'shopping-bag',
            'href' => $hotel ? route('orders.listv2', ['hotel_id' => $hotel->id]) : route('hotel.create'),
            'active' => request()->routeIs('orders.*'),
            'disabled' => ! $hotel,
        ],
        [
            'label' => 'Guests',
            'icon' => 'users',
            'href' => $hotel ? route('bookings.list', ['id' => $hotel->id]) : route('hotel.create'),
            'active' => request()->routeIs('bookings.*'),
            'disabled' => ! $hotel,
        ],
        [
            'label' => 'Performance',
            'icon' => 'line-chart',
            'href' => route('performance.index', ['hotel_id' => $currentHotelId]),
            'active' => request()->routeIs('performance.*'),
        ],
        [
            'label' => 'Calendar',
            'icon' => 'calendar-days',
            'href' => $hotel ? route('calendar.list-product-grid', ['id' => $hotel->id]) : route('hotel.create'),
            'active' => request()->routeIs('calendar.*'),
            'disabled' => ! $hotel,
        ],
        [
            'label' => 'Fulfilment',
            'icon' => 'key-round',
            'href' => route('fulfilment-keys.list'),
            'active' => request()->routeIs('fulfilment-keys.*'),
        ],
        [
            'label' => 'Account Settings',
            'icon' => 'settings',
            'href' => route('profile.edit'),
            'active' => request()->routeIs('profile.*'),
        ],
        [
            'label' => 'Info Pages',
            'icon' => 'book-open-check',
            'href' => '#',
            'active' => false,
            'disabled' => true,
            'badge' => 'Soon',
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-SJTMEB0Y01"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-SJTMEB0Y01');
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Enhance My Stay' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ $favicon ?? asset('favicon.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=grandstander:700,900|open-sans:400,700,800,900" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/builder.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="font-sans antialiased bg-slate-950 text-slate-900">

<div class="relative min-h-screen overflow-hidden">
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-gradient-to-br from-indigo-500/40 via-sky-400/30 to-transparent blur-3xl"></div>
        <div class="absolute right-[-18rem] top-1/2 h-[28rem] w-[28rem] -translate-y-1/2 rounded-full bg-gradient-to-br from-sky-500/40 via-emerald-400/30 to-transparent blur-3xl"></div>
        <div class="absolute bottom-[-14rem] left-1/2 h-[26rem] w-[26rem] -translate-x-1/2 rounded-full bg-gradient-to-br from-purple-500/35 via-pink-400/25 to-transparent blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto flex min-h-screen w-full flex-col px-4 py-6 text-slate-900 sm:px-6 lg:px-10">
        <div
            x-data="{ sidebarOpen: false }"
            class="flex min-h-[calc(100vh-3rem)] flex-1 overflow-hidden rounded-3xl border border-white/10 bg-white/15 shadow-[0_45px_120px_-60px_rgba(15,23,42,0.9)] backdrop-blur-2xl lg:flex-row"

        >
        <!-- Mobile sidebar -->
        <div
            x-cloak
            x-show="sidebarOpen"
            class="fixed inset-0 z-40 flex lg:hidden"
            role="dialog"
            aria-modal="true"
        >
            <div class="fixed inset-0 bg-slate-950/70 backdrop-blur" @click="sidebarOpen = false"></div>

            <aside class="relative ml-0 h-full w-72 max-w-full bg-gradient-to-b from-indigo-900 via-slate-900 to-slate-950 text-white shadow-2xl shadow-slate-950/40">

                <div class="flex items-center justify-between border-b border-white/5 px-6 py-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <img src="{{ asset('img/EMSLogo.png') }}" alt="Enhance My Stay" class="h-8">
                    </a>
                    <button
                        type="button"
                        @click="sidebarOpen = false"
                        class="inline-flex items-center justify-center rounded-full p-2 text-white/70 transition hover:bg-white/10 hover:text-white"
                    >
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
                    @foreach($navigation ?? [] as $item)
                        <a
                            href="{{ $item['href'] }}"
                            @class([
                                'flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition',
                                'text-white/40 pointer-events-none opacity-60' => $item['disabled'] ?? false,
                                'bg-gradient-to-r from-indigo-500 via-purple-500 to-sky-500 text-white shadow-glow' => $item['active'],
                                'text-white/80 hover:bg-white/10 hover:text-white' => !($item['active']),
                            ])
                        >
                            <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
                            <span>{{ $item['label'] }}</span>
                            @if(!empty($item['badge']))
                                <span class="ml-auto inline-flex items-center rounded-full bg-white/10 px-2 py-0.5 text-xs font-semibold text-white">
                                    {{ $item['badge'] }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </nav>
                {{-- Property details removed from mobile sidebar per request --}}
                <div class="px-6 pb-6">
                    <a
                        href="{{ route('hotel.create') }}"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-400 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition hover:from-indigo-400 hover:via-sky-400 hover:to-emerald-300"
                    >
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Add new property
                    </a>
                </div>
            </aside>
        </div>

        <!-- Desktop sidebar -->

        <aside class="hidden text-white lg:flex lg:w-72 lg:flex-col lg:border-r lg:border-white/10 lg:bg-gradient-to-b lg:from-indigo-900/95 lg:via-slate-900/90 lg:to-slate-950/95 lg:px-6 lg:py-8 lg:backdrop-blur-xl">

            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <img src="{{ asset('img/EMSLogo.png') }}" alt="Enhance My Stay" class="h-9">
                </a>
            </div>
            {{-- Property details removed from desktop sidebar per request --}}
            <nav class="mt-10 flex-1 space-y-1">
                @foreach($navigation ?? [] as $item)
                    <a
                        href="{{ $item['href'] }}"
                        @class([
                            'group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition',
                            'text-white/40 pointer-events-none opacity-60' => $item['disabled'] ?? false,
                            'bg-gradient-to-r from-indigo-500 via-purple-500 to-sky-500 text-white shadow-glow' => $item['active'],
                            'text-white/80 hover:bg-white/10 hover:text-white' => !($item['active']),
                        ])
                    >
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/5 transition group-hover:bg-white/10">
                            <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5"></i>
                        </span>
                        <span>{{ $item['label'] }}</span>
                        @if(!empty($item['badge']))
                            <span class="ml-auto inline-flex items-center rounded-full bg-white/10 px-2 py-0.5 text-xs font-semibold text-white">
                                {{ $item['badge'] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </nav>
            <div class="mt-8 rounded-2xl border border-white/10 bg-white/5 p-5 text-sm text-white/80">
                <p class="font-semibold text-white">Need help?</p>
                <p class="mt-1">Visit our help centre for onboarding guides, best practices, and support.</p>
                <a href="mailto:support@enhancemystay.com" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-white transition hover:text-sky-200">
                    <i data-lucide="life-buoy" class="w-4 h-4"></i>
                    Contact support
                </a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col bg-white/95 text-slate-900">

            <header class="sticky top-0 z-30 border-b border-slate-200/60 bg-gradient-to-r from-white/95 via-sky-50 to-emerald-50 backdrop-blur-xl shadow-[0_25px_65px_-45px_rgba(15,23,42,0.45)]">

                <div class="flex h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-10">
                    <div class="flex items-center">
                        <button
                            type="button"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200/70 bg-white/60 p-2 text-slate-500 transition hover:border-slate-300 hover:bg-white hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 lg:hidden"
                            @click="sidebarOpen = true"
                        >
                            <i data-lucide="menu" class="h-5 w-5"></i>
                        </button>
                    </div>
                    <div class="flex items-center gap-4">

                        <div class="hidden sm:flex items-center gap-3 rounded-2xl border border-slate-200/70 bg-gradient-to-r from-indigo-100/80 via-sky-100 to-emerald-100/80 px-3 py-2 text-sm text-slate-600 shadow-sm">
                            <i data-lucide="bell" class="h-4 w-4 text-indigo-500"></i>
                            <span class="text-slate-700">Notifications</span>

                        </div>
                        <div class="flex items-center gap-3">
                            <div class="hidden text-right sm:block">
                                <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name ?? 'Account' }}</p>
                                <p class="text-xs text-slate-500">{{ auth()->user()->email ?? '' }}</p>
                            </div>
                            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-indigo-600 via-sky-500 to-emerald-400 text-sm font-semibold uppercase text-white shadow-lg shadow-indigo-500/25">
                                {{ collect(explode(' ', auth()->user()->name ?? ''))
                                    ->map(fn($part) => substr($part, 0, 1))
                                    ->join('') ?: 'EM' }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-gradient-to-br from-white via-sky-50/80 to-indigo-50/70 px-4 py-10 sm:px-6 lg:px-10">
                <div class="mx-auto w-full space-y-10">
                    @if($activeHotel)
                        <div class="flex flex-wrap items-center gap-4 rounded-3xl border border-indigo-100/60 bg-white/80 px-6 py-4 shadow-sm shadow-indigo-200/40">
                            <div class="flex items-center gap-3">
                                @if($activeHotel->logo)
                                    <img src="{{ $activeHotel->logo }}" alt="{{ $activeHotel->name }} logo" class="h-10 w-10 rounded-2xl object-cover ring-2 ring-indigo-100">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-100 text-sm font-semibold uppercase text-indigo-700">
                                        {{ collect(explode(' ', $activeHotel->name ?? ''))
                                            ->map(fn($part) => substr($part, 0, 1))
                                            ->join('') ?: 'EM' }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500">Active property</p>
                                    <p class="text-base font-semibold text-slate-900">{{ $activeHotel->name }}</p>
                                </div>
                            </div>
                            <div class="flex w-full flex-col gap-3 sm:ml-auto sm:w-auto sm:flex-row sm:items-center sm:justify-end sm:gap-4">
                                <div class="w-full sm:w-auto sm:max-w-sm">
                                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Your upsell link</p>
                                    <div class="mt-1 flex items-center gap-2 rounded-2xl border border-indigo-100 bg-white/90 px-3 py-2 shadow-sm">
                                        <input
                                            id="active-hotel-welcome-url"
                                            type="text"
                                            readonly
                                            class="w-full bg-transparent text-sm text-slate-600"
                                            value="{{ env('APP_URL') }}/hotel/{{ $activeHotel->slug }}/welcome"
                                        >
                                        <button
                                            type="button"
                                            id="copy-active-hotel-link"
                                            class="inline-flex items-center gap-1 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100"
                                        >
                                            <i data-lucide="copy" class="h-3.5 w-3.5"></i>
                                            Copy
                                        </button>
                                    </div>
                                    <p
                                        id="active-hotel-link-confirmation"
                                        data-default-message="Share this link with guests to showcase your curated marketplace."
                                        class="mt-2 text-xs text-slate-500"
                                    >
                                        Share this link with guests to showcase your curated marketplace.
                                    </p>
                                </div>
                                @if($hotels->count() > 1)
                                    <a
                                        href="{{ route('hotel.edit', ['id' => $activeHotel->id]) }}"
                                        class="inline-flex items-center gap-2 rounded-xl border border-indigo-100 bg-indigo-50/70 px-3 py-1.5 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-100"
                                    >
                                        <i data-lucide="sparkles" class="h-3.5 w-3.5"></i>
                                        Manage brand
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                    @isset($header)
                        <div class="rounded-3xl border border-indigo-100/70 bg-gradient-to-r from-white via-indigo-50 to-sky-50 p-8 shadow-lg shadow-indigo-500/10">

                            {{ $header }}
                        </div>
                    @endisset
                    <div class="space-y-10">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    const refreshLucideIcons = () => {
        if (window.lucide) {
            window.lucide.createIcons();
        }
    };

    const initializeActiveHotelLinkCopy = () => {
        const copyButton = document.getElementById('copy-active-hotel-link');
        const copyInput = document.getElementById('active-hotel-welcome-url');
        const confirmationText = document.getElementById('active-hotel-link-confirmation');

        if (!copyButton || copyButton.dataset.initialized === 'true' || !copyInput || !confirmationText) {
            return;
        }

        copyButton.dataset.initialized = 'true';
        const defaultMessage = confirmationText.dataset.defaultMessage || confirmationText.textContent;

        const handleSuccess = () => {
            confirmationText.textContent = 'Copied to clipboard';
            setTimeout(() => {
                confirmationText.textContent = defaultMessage;
            }, 2000);
        };

        const handleFailure = () => {
            confirmationText.textContent = 'Unable to copy link';
            setTimeout(() => {
                confirmationText.textContent = defaultMessage;
            }, 2000);
        };

        const fallbackCopy = () => {
            try {
                if (document.execCommand('copy')) {
                    handleSuccess();
                } else {
                    handleFailure();
                }
            } catch (error) {
                handleFailure();
            }
        };

        copyButton.addEventListener('click', () => {
            copyInput.focus();
            copyInput.select();
            copyInput.setSelectionRange(0, copyInput.value.length);

            if (navigator.clipboard?.writeText) {
                navigator.clipboard
                    .writeText(copyInput.value)
                    .then(() => {
                        handleSuccess();
                        copyInput.blur();
                    })
                    .catch(() => {
                        fallbackCopy();
                        copyInput.blur();
                    });
            } else {
                fallbackCopy();
                copyInput.blur();
            }
        });
    };

    document.addEventListener('alpine:init', refreshLucideIcons);
    document.addEventListener('DOMContentLoaded', refreshLucideIcons);
    document.addEventListener('turbo:load', refreshLucideIcons);
    document.addEventListener('livewire:navigated', refreshLucideIcons);

    document.addEventListener('DOMContentLoaded', initializeActiveHotelLinkCopy);
    document.addEventListener('turbo:load', initializeActiveHotelLinkCopy);
    document.addEventListener('livewire:navigated', initializeActiveHotelLinkCopy);
</script>
</body>
</html>
