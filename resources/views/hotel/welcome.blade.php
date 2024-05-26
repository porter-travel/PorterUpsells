<x-guest-layout>
    <div class="mt-28 mx-4">
        <div class="bg-pink px-8 pt-20 pb-12 max-w-[667px] mx-auto rounded-3xl relative">
            <div class="absolute -top-24 bg-yellow px-8 py-6 left-1/2 -translate-x-1/2 rounded-3xl">
                <img style="width: 80px; height: 114px; object-fit: contain" src="/img/hank.png" alt="hotel"
                     class=""/>
            </div>

            <p class="text-black text-center grandstander text-2xl">
                Personalise your upcoming stay at {{$hotel->name}}
            </p>
            <p class="open-sans text-[#5a5a5a] text-center">In partnership with Enhance My Stay</p>

            <form method="post" action="/createSession">

                <input type="hidden" name="hotel_id" value="{{$hotel->id}}">
                @csrf
                <div class="mt-2 max-w-[600px] mx-auto">
                    <x-input-label class="text-black open-sans" for="name" :value="__('Name')"/>
                    <x-text-input id="name" class="block mt-1 w-full p-4" type="text" name="name"
                                  :value="$name ?: old('name')"
                                  required placeholder="Name"/>
                    <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                </div>

                <div class="flex flex-wrap sm:flex-nowrap items-center justify-between max-w-[600px] mx-auto">
                <div class="mt-2  basis-full sm:basis-1/2 sm:pr-2">
                    <x-input-label class="text-black open-sans" for="arrival-date" :value="__('Arrival Date')"/>
                    <x-text-input id="arrival-date" class="block mt-1 w-full p-4" type="date" name="arrival_date"
                                  :value="$arrival_date ?: old('arrival_date')"
                                  required placeholder="Arrival Date"/>
                    <x-input-error :messages="$errors->get('arrival_date')" class="mt-2"/>
                </div>

                <div class="mt-2  basis-full sm:basis-1/2 sm:pl-2">
                    <x-input-label class="text-black open-sans" for="departure-date" :value="__('Departure Date')"/>
                    <x-text-input id="departure-date" class="block mt-1 w-full p-4" type="date" name="departure_date"
                                  :value="$departure_date ?: old('departure_date')"
                                  required placeholder="Departure Date"/>
                    <x-input-error :messages="$errors->get('departure_date')" class="mt-2"/>
                </div>


                </div>
                <div class="mt-2 max-w-[600px] mx-auto ">
                    <x-input-label class="text-black open-sans" for="email-address" :value="__('Email Address')"/>
                    <x-text-input id="email-address" class="block mt-1 w-full p-4" type="email" name="email_address"
                                  :value="$email_address ?: old('email_address')"
                                  required placeholder="Email Address"/>
                    <x-input-error :messages="$errors->get('email_address')" class="mt-2"/>
                </div>
                <div class="max-w-[300px] mx-auto">
                <x-primary-button class="w-full justify-center mt-4">Personalise my stay</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
