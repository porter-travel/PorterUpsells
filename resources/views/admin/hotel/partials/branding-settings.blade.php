<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="py-6 text-gray-900">
        <h2 class="text-2xl font-bold mb-6">Branding</h2>
        <form enctype="multipart/form-data" method="post" action="/admin/hotel/{{$hotel->id}}/update">
            @csrf
            <div class="mt-4">
                <x-input-label class="text-black font-sans" for="name" :value="__('Name')"/>
                <x-text-input id="name" class="block mt-1 w-full px-3 py-2" type="text" name="name"
                              :value="$hotel->name"
                              required placeholder="Name"/>
                <x-input-error :messages="$errors->get('name')" class="mt-2"/>
            </div>
            <div class="mt-4">
                <x-input-label class="text-black font-sans" for="address" :value="__('Address')"/>
                <x-text-input id="address" class="block mt-1 w-full px-3 py-2" type="text" name="address"
                              :value="$hotel->address"
                              required placeholder="Address"/>
                <x-input-error :messages="$errors->get('address')" class="mt-2"/>
            </div>

            <div class="mt-4">
                <x-input-label class="text-black font-sans" for="email_address"
                               :value="__('Email Address')"/>
                <x-text-input id="email_address" class="block mt-1 w-full px-3 py-2" type="text"
                              name="email_address"
                              :value="$hotel->email_address"
                              required placeholder="Email Address"/>
                <x-input-error :messages="$errors->get('email_address')" class="mt-2"/>
            </div>
            <div class="text-right">
                <x-primary-button dusk="update-hotel-details" class=" mt-4">Update
                </x-primary-button>
            </div>
            </div>
        </form>
        <form class="border-t border-[#C4C4C4] mt-4 pt-4 flex items-end justify-between"
              enctype="multipart/form-data" method="post" action="/admin/hotel/{{$hotel->id}}/update">
            @csrf
            <div class="flex items-end justify-start">
                <div class="mr-12">
                    <p class="open-sans text-2xl mb-6">Logo</p>
                    <img src="{{$hotel->logo}}" alt="hotel" class="h-[70px] rounded-3xl mr-2"/>
                </div>

                <div class="mt-4">
                    <x-input-label class="text-black font-sans sr-only" for="logo" :value="__('Logo')"/>
                    <input type="file" name="logo" id="logo" value="{{$hotel->logo}}">
                </div>
            </div>
            <div>
                <x-primary-button class=" mt-4">Update</x-primary-button>
            </div>
        </form>

        <form class="border-t border-[#C4C4C4] mt-4 pt-4 flex items-end justify-between"
              enctype="multipart/form-data" method="post" action="/admin/hotel/{{$hotel->id}}/update">
            @csrf
            <div class="flex items-end justify-start">
                <div class="mr-12">
                    <p class="open-sans text-2xl mb-6">Cover Image</p>
                    <img alt="current featured image" class="w-[140px] h-auto"
                         src="{{$hotel->featured_image}}">
                </div>
                <x-input-label class="text-black font-sans sr-only" for="featured_image"
                               :value="__('Featured Image')"/>
                <input type="file" name="featured_image" id="featured_image">
            </div>
            <div>
                <x-primary-button class="mt-4">Update</x-primary-button>
            </div>
        </form>

        <form class="border-t border-[#C4C4C4] mt-4 pt-4 " method="post"
              action="/admin/hotel/{{$hotel->id}}/update">

            @include('admin.hotel.partials.colour-scheme', ['hotel' => $hotel])
            <div class="text-right">
                <x-primary-button class=" mt-4">Update
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
