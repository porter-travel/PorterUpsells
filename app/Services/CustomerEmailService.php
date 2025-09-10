<?php

namespace App\Services;

use App\Jobs\TrackEmailSends;
use App\Models\CustomerEmail;
use App\Models\HotelMeta;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class CustomerEmailService
{
    public function setupEmailSchedule($params)
    {

        //WE want to remove this and replace with the new schedule if it exists
        //For now run both in parallel and when the new templates are ready for all customers we can remove this old code
        foreach ($params['days'] as $email) {
            $customer_email = new CustomerEmail(['booking_id' => $params['booking']->id]);

            if (is_numeric($email)) {
                $customer_email->email_type = 'pre-arrival';
                $customer_email->scheduled_at = Carbon::parse($params['arrival_date'])->subDays($email)->setTime(11, 30);
            } else {
                $customer_email->email_type = $email;
                $customer_email->scheduled_at = Carbon::now();
                Mail::to($params['email_address'])->send(new \App\Mail\CustomerEmail($params['hotel'], $params['content']));
                $customer_email->sent_at = Carbon::now();
                TrackEmailSends::dispatch($params['hotel']->id);
            }

            $customer_email->save();
        }


        //This is the new code to handle the new email schedule if it exists

        $new_email_schedule = HotelMeta::where('hotel_id', $params['hotel']->id)
            ->where('key', 'custom-email-schedule')
            ->first();
        if (!$new_email_schedule) {
            return;
        }

        $schedule = json_decode($new_email_schedule->value);

        foreach ($schedule as $item) {
            $customer_email = new CustomerEmail(['booking_id' => $params['booking']->id]);
            $customer_email->email_type = 'customised-email';
            $days = $item->days;

            if ($item->when_to_send == 'before_arrival') {
                if (!isset($params['arrival_date']))
                    continue;
                $customer_email->scheduled_at = Carbon::parse($params['arrival_date'])->subDays($days)->setTimeFromTimeString($item->time);
            } elseif ($item->when_to_send == 'after_arrival') {
                if (!isset($params['arrival_date']))
                    continue;
                $customer_email->scheduled_at = Carbon::parse($params['arrival_date'])->addDays($days)->setTimeFromTimeString($item->time);
            } elseif ($item->when_to_send == 'before_departure') {
                if (!isset($params['departure_date']))
                    continue;
                $customer_email->scheduled_at = Carbon::parse($params['departure_date'])->subDays($days)->setTimeFromTimeString($item->time);
            } elseif ($item->when_to_send == 'after_departure') {
                if (!isset($params['departure_date']))
                    continue;
                $customer_email->scheduled_at = Carbon::parse($params['departure_date'])->addDays($days)->setTimeFromTimeString($item->time);
            }
            if(!$customer_email->scheduled_at){

            dd($customer_email, $item, $params);
            }
            $customer_email->hotel_email_id = $item->email_id;
            $customer_email->save();
        }


    }
}
