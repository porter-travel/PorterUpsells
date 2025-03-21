<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;

    use SoftDeletes;
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function variations()
    {
        return $this->hasMany(Variation::class)->orderBy('order');
    }

    public function specifics()
    {
        return $this->hasMany(ProductSpecific::class);
    }

    public function unavailabilities()
    {
        return $this->hasMany(Unavailability::class);
    }

    public function calendarBookings()
    {
        return $this->belongsToMany(CalendarBooking::class);

    }

    public function productViews()
    {
        return $this->hasMany(ProductViews::class);
    }


}
