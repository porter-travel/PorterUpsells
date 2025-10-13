<x-hotel-admin-layout :hotel="$hotel">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                <img src="{{$hotel->logo}}" alt="hotel" class="h-[70px] rounded-3xl mr-2"/>
                <h2 class="font-extrabold open-sans text-2xl text-black leading-tight uppercase">
                    {{ $hotel->name }}
                </h2>
            </div>
            <div>
                <p id="confirmation-text" class="open-sans font-semibold">Your upsell link</p>
                <div class="flex items-center">
                    <input id="hotel-welcome-url" type="text" disabled class="border-darkGrey/50 rounded-lg mr-4"
                           value="{{env('APP_URL')}}/hotel/{{$hotel->slug}}/welcome">
                    <span class="copy-label cursor-pointer flex px-8 py-2 bg-[#E4E4E4] rounded-full"
                          onclick="copyToClipboard()">
                    <svg class="mr-2" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.93103 6.92931H20.069C21.1745 6.92931 22.0707 7.82551 22.0707 8.93103V20.069C22.0707 21.1745 21.1745 22.0707 20.069 22.0707H8.93103C7.82551 22.0707 6.92931 21.1745 6.92931 20.069V8.93103C6.92931 7.82551 7.82551 6.92931 8.93103 6.92931ZM8.93103 5.95C7.28465 5.95 5.95 7.28465 5.95 8.93103V20.069C5.95 21.7153 7.28465 23.05 8.93103 23.05H20.069C21.7153 23.05 23.05 21.7153 23.05 20.069V8.93103C23.05 7.28465 21.7153 5.95 20.069 5.95H8.93103Z"
                            fill="black" stroke="black" stroke-width="0.1"/>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M2.33657 2.73583C1.97476 3.23834 1.85 3.85412 1.85 4.25833V14.7417C1.85 15.3848 1.99401 15.8337 2.19577 16.1523C2.39751 16.4708 2.67517 16.6877 2.98897 16.8371C3.63506 17.1448 4.3929 17.15 4.825 17.15V18L4.80907 18C4.38355 18.0001 3.45442 18.0002 2.62353 17.6045C2.19358 17.3998 1.78061 17.0854 1.47767 16.6071C1.17474 16.1288 1 15.5152 1 14.7417V4.25833C1 3.7181 1.15857 2.91721 1.64676 2.23917C2.15056 1.53946 2.98648 1 4.25833 1H14.7417C15.2819 1 16.0828 1.15857 16.7608 1.64676C17.4605 2.15056 18 2.98648 18 4.25833V5.10833H17.15V4.25833C17.15 3.26352 16.745 2.68277 16.2642 2.33657C15.7617 1.97476 15.1459 1.85 14.7417 1.85H4.25833C3.26352 1.85 2.68277 2.25499 2.33657 2.73583Z"
                              fill="black" stroke="black" stroke-width="0.3"/>
                    </svg>

                    Copy</span>

                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('admin.hotel.partials.branding-settings')
        </div>
    </div>

    <script>
        function copyToClipboard() {
            const copyText = document.getElementById("hotel-welcome-url");
            copyText.select();
            copyText.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(copyText.value);
            const confirmationText = document.getElementById("confirmation-text");
            confirmationText.innerHTML = "Copied!";
            setTimeout(() => {
                confirmationText.innerHTML = "Your upsell link";
            }, 2000);
        }
    </script>
</x-hotel-admin-layout>
