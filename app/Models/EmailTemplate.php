<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'hotel_id',
        'name',
        'subject',
        'body',
        'type',
        'is_active',
        'when_to_send',
        'days',
        'time',
        'hotel_id'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
