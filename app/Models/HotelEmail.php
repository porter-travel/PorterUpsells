<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'email_type',
        'key_message',
        'button_text',
        'featured_products',
        'additional_information'
    ];


    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public static function createStandardTemplates($hotel_id){
        $email = new HotelEmail();
        $email->hotel_id = $hotel_id;
        $email->key_message = "Hi [guest_name],

We can’t wait to welcome you to [hotel_name] in just [days_until_checkin].

To make sure you have the best experience possible, we’ve partnered with Enhance My Stay
to give you the opportunity to personalise your hotel experience with us.

Ready to enhance your stay? Click below to view our special upsell offers.";

        $email->button_text = "View Offers";
        $email->featured_products = json_encode([0,0,0,0]);
        $email->additional_information = "If you have any questions or need assistance, please don’t hesitate to contact us at [hotel_email_address].";
        $email->email_type = "pre-arrival-email";
        $email->save();
    }

    public static function createv2StandardTemplates(){
        $email = new HotelEmail();
        $email->key_message = "Hi [guest_name],

We can’t wait to welcome you to [hotel_name] in just [days_until_checkin].

To make sure you have the best experience possible, we’ve partnered with Enhance My Stay
to give you the opportunity to personalise your hotel experience with us.

Ready to enhance your stay? Click below to view our special upsell offers.";

        $email->button_text = "View Offers";
        $email->featured_products = [0,0,0,0];
        $email->additional_information = "If you have any questions or need assistance, please don’t hesitate to contact us at [hotel_email_address].";
        return $email;
    }


}
