<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bookings for: ') . $hotel->name }}
        </h2>
        <a href="{{route('booking.create', ['id' => $hotel->id])}}" class="text-black font-bold border rounded-xl p-4 hover:bg-mint">Create Booking</a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="get">
                        @csrf
                        <div class="flex items-center pb-6">
                            <div>
                                <label>Start Date
                                    <input type="date" name="start_date" value="{{$startDate}}"></label>
                            </div>

                            <div class="mx-4">
                                <label>End Date
                                    <input type="date" name="end_date" value="{{$endDate}}"></label>
                            </div>
                            <div>
                                <x-secondary-button type="submit">Filter</x-secondary-button>
                            </div>
                        </div>
                    </form>

                    @if(count($bookings) > 0)

                        <div class="flex flex-wrap">
                            <table class="w-full rounded">
                                <thead>
                                <tr class="text-left bg-grey">
                                    <th class="p-2">Booking Ref</th>
                                    <th class="p-2">Name</th>
                                    <th class="p-2">Arrival Date</th>
                                    <th class="p-2">Departure Date</th>
                                    <th class="p-2 ">Email Address</th>
                                    <th></th>
                                </tr>
                                </thead>
                                @foreach($bookings as $booking)

                                    {{--                                    {{dd($order->order_items)}}--}}

                                    <tr class="border">
                                        <td class="p-2">{{$booking->booking_ref}}</td>
                                        <td class="p-2">{{$booking->name}}</td>
                                        <td class="p-2">{{$booking->arrival_date}}</td>
                                        <td class="p-2">{{$booking->departure_date}}</td>
                                        <td class="p-2">{{$booking->email_address}}</td>
                                        <td></td>
                                    </tr>

                                @endforeach
                            </table>

                        </div>

                    @else

                        <p>No orders to show</p>
                    @endif


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
