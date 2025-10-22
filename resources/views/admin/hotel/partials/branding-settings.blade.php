<div class="space-y-8">
    <form enctype="multipart/form-data" method="post" action="/admin/hotel/{{ $hotel->id }}/update" class="space-y-6 rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8">
        @csrf
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Property details</h2>
                <p class="text-sm text-slate-500">Update the information that appears to guests throughout the concierge.</p>
            </div>
            <x-primary-button dusk="update-hotel-details" class="mt-2 sm:mt-0">Save changes</x-primary-button>
        </div>
        <div class="grid gap-6 md:grid-cols-2">
            <div class="space-y-2">
                <x-input-label class="text-slate-700" for="name" :value="__('Name')" />
                <x-text-input id="name" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm" type="text" name="name" :value="$hotel->name" required placeholder="Name" />
                <x-input-error :messages="$errors->get('name')" class="text-xs text-rose-600" />
            </div>
            <div class="space-y-2">
                <x-input-label class="text-slate-700" for="address" :value="__('Address')" />
                <x-text-input id="address" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm" type="text" name="address" :value="$hotel->address" required placeholder="Address" />
                <x-input-error :messages="$errors->get('address')" class="text-xs text-rose-600" />
            </div>
            <div class="space-y-2 md:col-span-2">
                <x-input-label class="text-slate-700" for="email_address" :value="__('Email Address')" />
                <x-text-input id="email_address" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm" type="email" name="email_address" :value="$hotel->email_address" required placeholder="Email Address" />
                <x-input-error :messages="$errors->get('email_address')" class="text-xs text-rose-600" />
            </div>
        </div>
    </form>

    <form class="flex flex-col gap-6 rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-8" enctype="multipart/form-data" method="post" action="/admin/hotel/{{ $hotel->id }}/update">
        @csrf
        <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center">
            <div class="space-y-2">
                <p class="text-sm font-semibold text-slate-900">Logo</p>
                <p class="text-xs text-slate-500">Use a transparent PNG for best results.</p>
                <input type="file" name="logo" id="logo" value="{{ $hotel->logo }}" class="text-sm text-slate-600">
            </div>
            <img src="{{ $hotel->logo }}" alt="hotel logo" class="h-16 w-16 rounded-2xl object-cover ring-2 ring-indigo-100" />
        </div>
        <x-primary-button class="self-start sm:self-center">Update logo</x-primary-button>
    </form>

    <form class="flex flex-col gap-6 rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-8" enctype="multipart/form-data" method="post" action="/admin/hotel/{{ $hotel->id }}/update">
        @csrf
        <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center">
            <div class="space-y-2">
                <p class="text-sm font-semibold text-slate-900">Cover image</p>
                <p class="text-xs text-slate-500">Displayed on your guest marketplace header.</p>
                <input type="file" name="featured_image" id="featured_image" class="text-sm text-slate-600">
            </div>
            <img alt="current featured image" class="h-20 w-36 rounded-2xl object-cover ring-2 ring-indigo-100" src="{{ $hotel->featured_image }}">
        </div>
        <x-primary-button class="self-start sm:self-center">Update cover</x-primary-button>
    </form>

    <form class="space-y-6 rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm sm:p-8" method="post" action="/admin/hotel/{{ $hotel->id }}/update">
        @csrf
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-semibold text-slate-900">Colour scheme</h3>
                <p class="text-sm text-slate-500">Adjust primary and accent colours used across your marketplace.</p>
            </div>
            <x-primary-button class="mt-2 sm:mt-0">Save palette</x-primary-button>
        </div>

        @include('admin.hotel.partials.colour-scheme', ['hotel' => $hotel])
    </form>
</div>
