<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\EmailTemplate;
use App\Services\CustomerEmailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\CustomerEmailController;
use Carbon\Carbon;

class SendScheduledEmails extends Command
{
// The name and signature of the console command
    protected $signature = 'emails:send-scheduled';

// The console command description
    protected $description = 'Send scheduled emails';

// Create a new command instance
    public function __construct()
    {
        parent::__construct();
    }

// Execute the console command
    public function handle()
    {
// Get the current time
        $now = Carbon::now();
        $oneHourAgo = $now->copy()->subHour();

// Find emails that are due to be sent (scheduled_at <= now and scheduled_at >= one hour ago and sent_at is null)
        $emails = DB::table('customer_emails')
            ->where('scheduled_at', '<=', $now)
            ->where('scheduled_at', '>=', $oneHourAgo)
            ->whereNull('sent_at')
            ->get(['id', 'booking_id', 'hotel_email_id', 'email_type']);
//
//var_dump($emails->toArray());
//        dd($emails);

        foreach ($emails as $email) {

            if($email->type == 'pre-arrival') {
//            var_dump($email);
                $booking = Booking::find($email->booking_id);
// Prepare request data to call CustomerEmailController@send
                $request = new \Illuminate\Http\Request();
                $request->replace([
                    'guest_name' => $booking->name,
                    'arrival_date' => $booking->arrival_date,
                    'departure_date' => $booking->departure_date,
                    'email_address' => $booking->email_address,
                    'booking_ref' => $booking->booking_ref,
                    'hotel_email_id' => $email->hotel_email_id,
                    'email_type' => $email->email_type,
                ]);

// Call the send method of CustomerEmailController
                $controller = new CustomerEmailController();
                $controller->send($request, $booking->hotel_id);

            } else {

                $service = new CustomerEmailService();
                $booking = Booking::find($email->booking_id);
                $emailTemplate = EmailTemplate::find($email->email_template_id);
                $service->sendTemplateEmail($booking, $emailTemplate);

            }

// Update the sent_at column to the current time
            DB::table('customer_emails')
                ->where('id', $email->id)
                ->update(['sent_at' => $now]);
        }

        return 0;
    }
}
