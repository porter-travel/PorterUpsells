<x-guest-layout>

    <x-slot:favicon>{{$hotel->logo}}</x-slot:favicon>


    @include('hotel.partials.css-overrides', ['hotel' => $hotel])

    <div class="mt-28 ">
        <div class="hotel-main-box-color box-shadow px-8 pt-16 pb-12 max-w-[667px] mx-auto rounded-3xl relative">
            <div class="absolute top-0 px-8 py-6 left-1/2 -translate-x-1/2 -translate-y-1/2 rounded-3xl">
                @include ('hotel.partials.hotel-logo', ['hotel' => $hotel])
            </div>

            <p class="hotel-main-box-text-color text-center font-bold open-sans text-2xl border-b border-darkGrey pb-6">
                Thank you for personalising your upcoming stay
            </p>

            <p class="text-xl font-bold open-sans my-4 hotel-main-box-text-color">Your Order</p>

            @if(isset($cartItems) && count($cartItems) > 0)

                @foreach($cartItems as $item)
                    @if(isset($item['image']))
                        <div class="flex items-center justify-start mb-4">
                            <div class="sm:w-[150px] sm:basis-[150px] w-[80px] basis-[80px] mr-4">
                                @include ('hotel.partials.product-image', ['item' => $item])
                            </div>

                            <div class="hotel-main-box-text-color basis-full">
                                <p><strong>{{$item['product_name']}}</strong></p>
                                @if($item['product_type'] == 'variable')
                                    <p>Options: {{$item['variation_name']}}</p>
                                @endif

                                <p>Date: {{ \Carbon\Carbon::parse($item['date'])->format('jS M') }}</p>
                                @if(isset($item['arrival_time']) && $item['arrival_time'])
                                    <p>Time: {{$item['arrival_time']}}</p>
                                @endif
                                <p class="cart-product-subtotal text-xl font-bold">
                                    <x-money-display :amount="$item['price'] * $item['quantity']"
                                                     :currency="$hotel->user->currency"></x-money-display>
                                   </p>

                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</x-guest-layout>
