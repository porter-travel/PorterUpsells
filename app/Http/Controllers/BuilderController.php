<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class BuilderController extends Controller
{
    public function addEmail($hotel_id){
        $hotel = Hotel::find($hotel_id);
        return view('admin.builder.builder', compact('hotel'));
    }
}
