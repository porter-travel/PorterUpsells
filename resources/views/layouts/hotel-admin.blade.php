@props(['hotel' => null])

@php
    $hotels = auth()->user()->hotels ?? [];
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
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r">
        <nav class="p-4 space-y-2 text-lg">
            <a href="{{ $hotel ? route('hotel.edit', ['id' => $hotel->id]) : '#' }}" class="block px-4 py-2 hover:bg-gray-100">Products</a>
            <a href="{{ $hotel ? route('orders.listv2', ['hotel_id' => $hotel->id]) : '#' }}" class="block px-4 py-2 hover:bg-gray-100">Orders</a>
            <a href="{{ $hotel ? route('bookings.list', ['id' => $hotel->id]) : '#' }}" class="block px-4 py-2 hover:bg-gray-100">Guests</a>
            <a href="{{ route('performance.index') }}" class="block px-4 py-2 hover:bg-gray-100">Performance</a>
            <a href="{{ $hotel ? route('calendar.list-product-grid', ['id' => $hotel->id]) : '#' }}" class="block px-4 py-2 hover:bg-gray-100">Calendar</a>
            <a href="{{ route('fulfilment-keys.list') }}" class="block px-4 py-2 hover:bg-gray-100">Fulfilment</a>
            <a href="{{ $hotel ? route('hotel.edit', ['id' => $hotel->id]) : '#' }}" class="block px-4 py-2 hover:bg-gray-100">Branding</a>
            <a href="{{ $hotel ? route('hotel.edit', ['id' => $hotel->id]) : '#' }}" class="block px-4 py-2 hover:bg-gray-100">Settings</a>
            <span class="block px-4 py-2 text-gray-400">Info Pages (Coming Soon)</span>
        </nav>
    </aside>
    <div class="flex-1 flex flex-col">
        <header class="h-16 bg-white border-b flex items-center justify-between px-4">
            <a href="{{ route('dashboard') }}">
                <img src="/img/logo.svg" alt="logo" class="h-8">
            </a>
            <div>
                <select onchange="if(this.value) window.location.href=this.value" class="border rounded p-2">
                    @foreach($hotels as $h)
                        <option value="{{ route('hotel.edit', ['id' => $h->id]) }}" @selected($hotel && $h->id === $hotel->id)>{{ $h->name }}</option>
                    @endforeach
                    <option value="{{ route('hotel.create') }}">Add New Property</option>
                </select>
            </div>
        </header>
        <main class="flex-1 p-6">
            @isset($header)
                <div class="mb-4">
                    {{ $header }}
                </div>
            @endisset
            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
