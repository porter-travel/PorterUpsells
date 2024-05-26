<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{

    function welcome(Request $request, $id)
    {

        $hotel = \App\Models\Hotel::find($id);
        $name = '';
        $booking_ref = '';
        $arrival_date = '';
        $departure_date = '';
        $email_address = '';

        if ($request->has('name')) {
            $name = $request->input('name');
        }

        if ($request->has('arrival_date')) {
            $arrival_date = $request->input('arrival_date');
        }

        if ($request->has('departure_date')) {
            $departure_date = $request->input('departure_date');
        }

        if ($request->has('email_address')) {
            $email_address = $request->input('email_address');
        }

        return view('hotel.welcome')->with(['hotel' => $hotel, 'name' => $name, 'booking_ref' => $booking_ref, 'arrival_date' => $arrival_date, 'email_address' => $email_address, 'departure_date' => $departure_date]);
    }


    function dashboard(Request $request, $id)
    {

        $data['name'] = $request->session()->get('name');
        $data['booking_ref'] = $request->session()->get('booking_ref');
        $data['arrival_date'] = $request->session()->get('arrival_date');

        $cart = session()->get('cart');

        $hotel = \App\Models\Hotel::find($id);

        $products = $hotel->products;

        $data['days_until_arrival'] = (strtotime($data['arrival_date']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);

        $data['dummy_items'] = [
            ['img' => 'breakfast.png', 'title' => 'Breakfast', 'price' => '£15.00', 'id' => '1'],
            ['img' => 'prosecco.png', 'title' => 'Bottle of Prosecco in your room', 'price' => '£21.99', 'id' => '1'],
            ['img' => 'romance.png', 'title' => 'Romance package including petals and wine', 'price' => '£49.99', 'id' => '1'],
            ['img' => 'massage.png', 'title' => 'Head massage in our spa facilities', 'price' => '£59.99', 'id' => '1']
        ];

        return view('hotel.dashboard', ['data' => $data, 'hotel' => $hotel, 'products' => $products, 'cart' => $cart])->with('id', $id);
    }

    function create(Request $request)
    {
        return view('admin.hotel.create');
    }

    function store(Request $request)
    {
        $hotel = new \App\Models\Hotel();

        $hotel->name = $request->name;
        $hotel->address = $request->address;
        $hotel->user_id = auth()->user()->id;


        if ($request->file('logo')) {
            $logoFilePath = $request->file('logo')->store('hotel-logos', 's3');

            $url = Storage::disk('s3')->url($logoFilePath);
            $hotel->logo = $url;
        }

        if ($request->file('featured_image')) {
            $featuredImageFilePath = $request->file('featured_image')->store('hotel-featured-images', 's3');
            $featuredImageUrl = Storage::disk('s3')->url($featuredImageFilePath);
            $hotel->featured_image = $featuredImageUrl;
        }

        $hotel->save();
        return redirect()->route('hotel.edit', ['id' => $hotel->id]);
    }

    public function edit(Request $request, $id)
    {
        $hotel = \App\Models\Hotel::find($id);
        return view('admin.hotel.edit', ['hotel' => $hotel]);
    }

    public function update(Request $request, $id)
    {
        $hotel = \App\Models\Hotel::find($id);

        if ($request->name) {
            $hotel->name = $request->name;
        }

        if ($request->address) {
            $hotel->address = $request->address;
        }

        if ($request->file('logo')) {
            $filePath = $request->file('logo')->store('hotel-logos', 's3');
            $url = Storage::disk('s3')->url($filePath);
            $hotel->logo = $url;
        }

        if ($request->file('featured_image')) {
            $filePath = $request->file('featured_image')->store('hotel-logos', 's3');
            $url = Storage::disk('s3')->url($filePath);
            $hotel->logo = $url;
        }

        $hotel->save();
        return redirect()->route('hotel.edit', ['id' => $hotel->id]);
    }
}
