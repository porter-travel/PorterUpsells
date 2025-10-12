@props(['hotel' => null])

@php
    $user = auth()->user();
    $hotels = collect($user?->hotels ?? []);
    $currentHotelId = $hotel?->id;

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
            'active' => request()->routeIs('hotel.edit'),
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
    <link rel="icon" type="image/x-icon" href="{{ $favicon ?? '/img/hank.png' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=grandstander:700,900|open-sans:400,700,800,900" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-900">
<div
    x-data="{ sidebarOpen: false }"
    class="min-h-screen flex bg-slate-50"
>
    <!-- Mobile sidebar -->
    <div
        x-cloak
        x-show="sidebarOpen"
        class="fixed inset-0 z-40 flex lg:hidden"
        role="dialog"
        aria-modal="true"
    >
        <div class="fixed inset-0 bg-slate-950/60" @click="sidebarOpen = false"></div>
        <aside class="relative ml-0 h-full w-72 max-w-full bg-white shadow-xl">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <img src="/img/logo.svg" alt="Enhance My Stay" class="h-8">
                </a>
                <button
                    type="button"
                    @click="sidebarOpen = false"
                    class="inline-flex items-center justify-center rounded-full p-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100"
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
                            'text-slate-400 pointer-events-none opacity-60' => $item['disabled'] ?? false,
                            'bg-slate-900 text-white shadow-lg shadow-slate-900/20' => $item['active'],
                            'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => !($item['active']),
                        ])
                    >
                        <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
                        <span>{{ $item['label'] }}</span>
                        @if(!empty($item['badge']))
                            <span class="ml-auto inline-flex items-center rounded-full bg-slate-900/10 px-2 py-0.5 text-xs font-semibold text-slate-900">
                                {{ $item['badge'] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </nav>
            <div class="px-6 pb-6">
                <a
                    href="{{ route('hotel.create') }}"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 via-purple-600 to-violet-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:from-indigo-500 hover:via-purple-500 hover:to-violet-500"
                >
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add new property
                </a>
            </div>
        </aside>
    </div>

    <!-- Desktop sidebar -->
    <aside class="hidden lg:flex lg:w-72 lg:flex-col lg:border-r lg:border-slate-200 lg:bg-white lg:px-6 lg:py-8">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <img src="/img/logo.svg" alt="Enhance My Stay" class="h-9">
            </a>
        </div>
        <div class="mt-10 rounded-2xl bg-gradient-to-br from-indigo-700 via-purple-700 to-violet-800 p-6 text-white shadow-xl shadow-indigo-800/30">
            <p class="text-sm uppercase tracking-widest text-indigo-100/80">Your portfolio</p>
            <p class="mt-3 text-3xl font-semibold text-white">{{ $hotels->count() }}</p>
            <p class="mt-2 text-sm text-indigo-100/80">Active properties connected to Enhance My Stay.</p>
            <a href="{{ route('hotel.create') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-indigo-600 shadow-sm transition hover:bg-indigo-50">
                <i data-lucide="plus" class="w-4 h-4"></i>
                New property
            </a>
        </div>
        <nav class="mt-10 flex-1 space-y-1">
            @foreach($navigation ?? [] as $item)
                <a
                    href="{{ $item['href'] }}"
                    @class([
                        'group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition',
                        'text-slate-400 pointer-events-none opacity-60' => $item['disabled'] ?? false,
                        'bg-slate-900 text-white shadow-lg shadow-slate-900/15' => $item['active'],
                        'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => !($item['active']),
                    ])
                >
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-900/5 group-hover:bg-slate-900/10">
                        <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5"></i>
                    </span>
                    <span>{{ $item['label'] }}</span>
                    @if(!empty($item['badge']))
                        <span class="ml-auto inline-flex items-center rounded-full bg-slate-900/10 px-2 py-0.5 text-xs font-semibold text-slate-900">
                            {{ $item['badge'] }}
                        </span>
                    @endif
                </a>
            @endforeach
        </nav>
        <div class="mt-8 rounded-2xl border border-slate-200 p-5 text-sm">
            <p class="font-semibold text-slate-900">Need help?</p>
            <p class="mt-1 text-slate-600">Visit our help centre for onboarding guides, best practices, and support.</p>
            <a href="mailto:support@enhancemystay.com" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-slate-900 hover:text-slate-700">
                <i data-lucide="life-buoy" class="w-4 h-4"></i>
                Contact support
            </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col">
        <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/80 backdrop-blur">
            <div class="flex h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-10">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 p-2 text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-400 lg:hidden"
                        @click="sidebarOpen = true"
                    >
                        <i data-lucide="menu" class="h-5 w-5"></i>
                    </button>
                    <div class="hidden md:flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-2">
                        <i data-lucide="search" class="h-4 w-4 text-slate-400"></i>
                        <input
                            type="search"
                            placeholder="Search anything..."
                            class="w-48 bg-transparent text-sm focus:outline-none"
                        >
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm">
                        <i data-lucide="bell" class="h-4 w-4 text-slate-400"></i>
                        <span class="text-slate-500">Notifications</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="hidden text-right sm:block">
                            <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name ?? 'Account' }}</p>
                            <p class="text-xs text-slate-500">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-slate-900 via-slate-800 to-slate-700 text-sm font-semibold uppercase text-white">
                            {{ collect(explode(' ', auth()->user()->name ?? ''))
                                ->map(fn($part) => substr($part, 0, 1))
                                ->join('') ?: 'EM' }}
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <main class="flex-1 px-4 py-8 sm:px-6 lg:px-10">
            @isset($header)
                <div class="mb-6">
                    {{ $header }}
                </div>
            @endisset
            {{ $slot }}
        </main>
    </div>
</div>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    const refreshLucideIcons = () => {
        if (window.lucide) {
            window.lucide.createIcons();
        }
    };

    document.addEventListener('alpine:init', refreshLucideIcons);
    document.addEventListener('DOMContentLoaded', refreshLucideIcons);
    document.addEventListener('turbo:load', refreshLucideIcons);
    document.addEventListener('livewire:navigated', refreshLucideIcons);
</script>
</body>
 </html>
