<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Hotel;
use Illuminate\Console\Command;
use App\Services\HotelBookings\HotelBookingsService;
use Carbon\Carbon;

class RefreshHighlevelBookings extends Command
{
    // The name and signature of the console command
    protected $signature = 'bookings:refresh';

    // The console command description
    protected $description = 'Refresh all future bookings where the guest hasnt signed in yet';

    // Create a new command instance
    public function __construct()
    {
        parent::__construct();
    }

    // Execute the console command
    // Will only update bookings where checkin is null
    public function handle()
    {
        $config =
            [
                "apiKey" => "3f3WkVeHuF1iwxTxeC8vn7oZYm00U3gu7d9jx6TQ",
                "host" => "https://api.stage.dev.high-level-software.com",
                //"host" => "https://api.high-level-software.com"
                "token" => "e57ab82f1d2c9c43",
                "secret" => "6453fd736fea0b6190e27331a318a8f39da15d41013474417a78cf96858d8a2b"
            ];
            $HotelBookingsService = new HotelBookingsService($config);
            foreach ($HotelBookingsService->fetchReservations() as $Reservation) {
                // Find hotel id
                $Booking = Booking::where("booking_ref", $Reservation->externalBookingId)->where("checkin", null)->first();
                if ($Booking != null && $Reservation->checkedInString!=null) {
                   
                    $Booking->checkin = strtotime($Reservation->checkedInString);
                    $Booking->save();
                }
            }
        
    }
}
