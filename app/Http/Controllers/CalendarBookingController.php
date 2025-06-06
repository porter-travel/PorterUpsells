<?php

namespace App\Http\Controllers;

use App\Helpers\Intervals;
use App\Models\CalendarBooking;
use App\Models\Hotel;
use App\Models\Product;
use App\Services\CalendarBookingService;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarBookingController extends Controller
{
    public function list($hotel_id, Request $request)
    {
        $hotel = Hotel::find($hotel_id);

        if ($hotel->user_id != auth()->user()->id && auth()->user()->role != 'superadmin') {
            return redirect()->route('dashboard');
        }

//        global $startDate, $endDate;
        $startDate = Carbon::now()->startOfDay();

        $endDate = Carbon::now()->addDays(7)->endOfDay();

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'));
            $endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'));
        }


        $output = CalendarBooking::where('hotel_id', $hotel_id)
            ->whereDate('date', '>=', $startDate->toDateString())
            ->whereDate('date', '<=', $endDate->toDateString())
            ->with(['order', 'order.items', 'order.items.product', 'order.items.meta'])
            ->get();

//dd($output);
        $groupedBookings = [];

        foreach ($output as $booking) {
            $bookingDate = $booking['date'];

            // Ensure the date exists in the grouped array
            if (!isset($groupedBookings[$bookingDate])) {
                $groupedBookings[$bookingDate] = [];
            }

            foreach ($booking['order']['items'] as $item) {
                // Extract meta information
                $meta = [];
                foreach ($item['meta'] as $metaItem) {
                    $meta[$metaItem['key']] = $metaItem['value'];
                }

                // Add the item to the grouped bookings
                $groupedBookings[$bookingDate][$item['id']] = [
                    'name' => $booking['order']['name'],
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'arrival_time' => $meta['arrival_time'] ?? null,
                    'end_time' => $meta['end_time'] ?? null,
                ];
            }
        }

//        dd($groupedBookings);

        return view('admin.calendar.list',
            [
                'orders' => $groupedBookings,
                'hotel' => $hotel,
                'startDate' => $startDate->format('Y-m-d'),
                'endDate' => $endDate->format('Y-m-d')
            ]);
    }

    public function listProductGrid($hotel_id, Request $request)
    {
        $hotel = Hotel::find($hotel_id);

        $products = $hotel->products->where('type', 'calendar');
        if ($products->isEmpty()) {
            return view('admin.calendar.list-product-grid', [
                'hotel' => $hotel,
                'products' => $products
            ]);
        }
        return $this->listBookingsForProduct($hotel_id, $products->first()->id, $request);


    }

    public function listBookingsForProduct($hotel_id, $product_id, Request $request)
    {
        $product = Product::find($product_id);
//        dd($product);
        $variations = $product->variations;
        $specifics = $product->specifics;
        $interval = $product->specifics->where('name', 'time_intervals')->first()->value;
        $availability = $product->specifics->where('name', 'concurrent_availability')->first()->value;
        $hotel = Hotel::find($hotel_id);
        $date = Carbon::now()->startOfDay();
        $products = $hotel->products->where('type', 'calendar');

        if ($request->has('date')) {
            $date = Carbon::createFromFormat('Y-m-d', $request->input('date'));
        }

        $day = strtolower($date->dayName);

        $step = Intervals::wordsToMinutes($interval);
        $startTime = $product->specifics->where('name', "start_time_{$day}")->first()->value;
        $endTime = $product->specifics->where('name', "end_time_{$day}")->first()->value;
        $start = strtotime($startTime);
        $end = strtotime($endTime);
        $availableTimes = [];
        // Generate times


        $i = 0;
        while ($i < (int)$availability) {
            for ($time = $start; $time + $step <= $end; $time += $step) {
                $availableTimes[$i][] = ['time' => date('H:i', $time)];
            }
            $i++;
        }


        $bookings = CalendarBooking::where('hotel_id', $hotel_id)
            ->where('product_id', $product_id)
            ->whereDate('date', $date->toDateString())
            ->with([])
            ->get();

        $CBS = new CalendarBookingService();

        $bookings = $CBS->processBookingsIntoGroups($bookings->toArray());

        $availableTimes = $this->mapBookingsToTimes($bookings, $availableTimes);


        return view('admin.calendar.list-bookings-for-product', [
            'product' => $product,
            'specifics' => $specifics,
            'interval' => $interval,
            'availability' => $availability,
            'availableTimes' => $availableTimes,
            'hotel' => $hotel,
            'bookings' => $bookings,
            'date' => $date->format('Y-m-d'),
            'tomorrow' => $date->copy()->addDay()->format('Y-m-d'),
            'yesterday' => $date->copy()->subDay()->format('Y-m-d'),
            'variations' => $variations,
            'products' => $products
        ]);
    }

    public function deleteBooking(Request $request)
    {
        $booking = CalendarBooking::find($request->booking_id);
        $hotel = Hotel::find($booking->hotel_id);

        if ($hotel->user_id != auth()->user()->id && auth()->user()->role != 'superadmin') {
            return redirect()->route('dashboard');
        }

        $childBookings = $booking->childBookings();

        if (is_countable($childBookings)) {
            foreach ($childBookings as $childBooking) {
                $childBooking->delete();
            }
        }

        $booking->delete();
        return redirect()->back();
    }



    public function mapBookingsToTimes($bookings, $timeSlots)
    {
        // Group bookings by their slot
        $groupedBookings = collect($bookings)->groupBy('slot');

        // Initialize the result array
        $result = [];

        // Iterate over each slot and its corresponding time range
        foreach ($timeSlots as $slotIndex => $timeRanges) {
            $slotData = [];
            $slotBookings = $groupedBookings->get($slotIndex, collect()); // Get bookings for this slot

            // Map each time range with corresponding booking or null
            foreach ($timeRanges as $timeRange) {
                $time = $timeRange['time'] . ':00';
                $matchingBooking = $slotBookings->firstWhere('start_time', $time);

                $slotData[] = [
                    'time' => $time,
                    'booking' => $matchingBooking ? $matchingBooking : null,
                ];
            }

            $result[$slotIndex] = $slotData;
        }


        return $result;
    }


    public function getFutureAvailabilityOnSameDayForProduct($product_id, Request $request)
    {
        $product = Product::find($product_id);
        $interval = $product->specifics->where('name', 'time_intervals')->first()->value;
        $availability = $product->specifics->where('name', 'concurrent_availability')->first()->value;
        $date = Carbon::createFromFormat('Y-m-d', $request->input('date'));

        $slot = $request->input('slot');
        $hotel_id = $product->hotel_id;
        $day = strtolower($date->dayName);
        $step = Intervals::wordsToMinutes($interval);
        if($request->start_time) {
            $startTime = $request->start_time;
            $only_exclude_booked_slots = false;
        } else {
            $only_exclude_booked_slots = true;
            $startTime = $product->specifics->where('name', "start_time_{$day}")->first()->value;
        }
        $endTime = $product->specifics->where('name', "end_time_{$day}")->first()->value;
        $start = strtotime($startTime);
        $end = strtotime($endTime);
        $availableTimes = [];

        $i = 0;
        while ($i < (int)$availability) {
            for ($time = $start; $time + $step <= $end; $time += $step) {
                $availableTimes[$i][] = ['time' => date('H:i', $time)];
            }

            // Add an extra step to the end of the list
            if ($time < $end + $step) {
                $availableTimes[$i][] = ['time' => date('H:i', $time)];
            }

            $i++;
        }

        $bookings = CalendarBooking::where('hotel_id', $hotel_id)
            ->where('product_id', $product_id)
            ->whereDate('date', $date->toDateString())
            ->with([])
            ->get();

//dd($availableTimes);
        $availableTimes = $this->mapBookingsToTimes($bookings, $availableTimes);
//        dd($availability, $availableTimes, $bookings);

//        dd($availableTimes);
        $removeItems = false;
        foreach ($availableTimes[$slot] as $key => $timeSlot) {

            if (!$removeItems && isset($request->booking_id) && $timeSlot['booking'] && ($timeSlot['booking']['id'] == $request->booking_id || $timeSlot['booking']['parent_booking_id'] == $request->booking_id)) {
                continue;
            }

            if ($timeSlot['time'] == $startTime) {
                unset($availableTimes[$slot][$key]);
            }
            if($only_exclude_booked_slots) {
                if ($timeSlot['booking'] != []) {
                    unset($availableTimes[$slot][$key]);
                }
            } else {


                if ($removeItems) {
                    unset($availableTimes[$slot][$key]);
                }
                if ($timeSlot['booking'] != null) {
                    $removeItems = true;
                }

            }
        }


        $output = [];
        foreach ($availableTimes[$slot] as $timeSlot) {
            $output[] = $timeSlot['time'];
        }

//        dd($output);

        return $output;
    }

    public function storeBooking($hotel_id, $product_id, Request $request)
    {

        $product = Product::find($product_id);
        $hotel = Hotel::find($hotel_id);
        $room = $request->input('room');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $slot = $request->input('slot');
        $date = Carbon::createFromFormat('Y-m-d', $request->input('date'));
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        $name = $request->input('name');

        if ($request->input('variation')) {
            //This won't work as there is no variation_id in the request
            $variation = $request->input('variation');
        } else {
            $variation = $product->variations->first()->id;
        }

        $interval = $product->specifics->where('name', 'time_intervals')->first()->value;
        $step = Intervals::wordsToMinutes($interval);
        $start = strtotime($startTime);
        $end = strtotime($endTime);
        $availableTimes = [];
        // Generate times


        for ($time = $start; $time + $step <= $end; $time += $step) {
            $availableTimes[] = ['time' => date('H:i', $time)];
        }

        // Add an extra step to the end of the list
        if ($time < $end + $step) {
            $availableTimes[] = ['time' => date('H:i', $time)];
        }


        if (count($availableTimes) > 1) {
//        dd($availableTimes);
            $bookings = [];

            for ($i = 0; $i < count($availableTimes) - 1; $i++) {
                $startTime = $availableTimes[$i]['time'];
                $endTime = $availableTimes[$i + 1]['time'];

                $booking = new CalendarBooking([
                    'hotel_id' => $hotel_id,
                    'product_id' => $product_id,
                    'variation_id' => $variation,
                    'date' => $date,
                    'qty' => 1,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'name' => $name,
                    'email' => $email,
                    'mobile' => $phone,
                    'room_number' => $room,
                    'status' => 'confirmed',
                    'slot' => $slot
                ]);

                $bookings[] = $booking;
            }

            $parent_booking_id = null;
// Save all bookings
            foreach ($bookings as $key => $booking) {
                if ($key > 0) {
                    $booking->parent_booking_id = $parent_booking_id;
                }
                $booking->save();
                if ($key == 0) {
                    $parent_booking_id = $booking->id;
                }
            }
        } else {
            $booking = new CalendarBooking([
                'hotel_id' => $hotel_id,
                'product_id' => $product_id,
                'variation_id' => $variation,
                'date' => $date,
                'qty' => 1,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'name' => $name,
                'email' => $email,
                'mobile' => $phone,
                'room_number' => $room,
                'status' => 'confirmed',
                'slot' => $slot
            ]);

            $booking->save();
        }


        return redirect()->route('calendar.list-bookings-for-product', ['hotel_id' => $hotel_id, 'product_id' => $product_id, 'date' => $date->format('Y-m-d')]);
    }

    public function updateBooking(Request $request)
    {

        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        $oldBooking = CalendarBooking::find($request->booking_id);
        $product = Product::find($oldBooking->product_id);
        $interval = $product->specifics->where('name', 'time_intervals')->first()->value;
        $step = Intervals::wordsToMinutes($interval);
        $start = strtotime($startTime);
        $end = strtotime($endTime);
        $availableTimes = [];
        // Generate times


        for ($time = $start; $time + $step <= $end; $time += $step) {
            $availableTimes[] = ['time' => date('H:i', $time)];
        }

        // Add an extra step to the end of the list
        if ($time < $end + $step) {
            $availableTimes[] = ['time' => date('H:i', $time)];
        }

        if (count($availableTimes) > 1) {
//        dd($availableTimes);
            $bookings = [];

            for ($i = 0; $i < count($availableTimes) - 1; $i++) {
                $startTime = $availableTimes[$i]['time'];
                $endTime = $availableTimes[$i + 1]['time'];

                $booking = new CalendarBooking([
                    'hotel_id' => $oldBooking->hotel_id,
                    'product_id' => $oldBooking->product_id,
                    'variation_id' => $oldBooking->variation_id,
                    'date' => $oldBooking->date,
                    'qty' => 1,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->phone,
                    'room_number' => $request->room,
                    'status' => 'confirmed',
                    'slot' => $request->slot
                ]);



                $bookings[] = $booking;
            }

//            dd($bookings);

            $parent_booking_id = null;
// Save all bookings
            foreach ($bookings as $key => $booking) {
                if ($key > 0) {
                    $booking->parent_booking_id = $parent_booking_id;
                }
                $booking->save();
                if ($key == 0) {
                    $parent_booking_id = $booking->id;
                }
            }
        } else {
            $booking = new CalendarBooking([
                'hotel_id' => $oldBooking->hotel_id,
                'product_id' => $oldBooking->product_id,
                'variation_id' => $oldBooking->variation_id,
                'date' => $oldBooking->date,
                'qty' => 1,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->phone,
                'room_number' => $request->room,
                'status' => 'confirmed',
                'slot' => $request->slot
            ]);

//            dd($booking);

            $booking->save();
        }

        $childBookings = $oldBooking->childBookings();
        $childBookings->each(function($childBooking){
            $childBooking->delete();
        });
        $oldBooking->delete();



        return redirect()->back();
    }
}
