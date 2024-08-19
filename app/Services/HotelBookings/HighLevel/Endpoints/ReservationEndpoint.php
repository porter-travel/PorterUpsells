<?php

namespace App\Services\HotelBookings\HighLevel\Endpoints;

use App\Services\HotelBookings\Entities\Reservation;
use App\Services\HotelBookings\HighLevel\ReservationMapper;

class ReservationEndpoint extends HighlevelEndpoint
{

  // e48fe378-fd81-11ee-9d39-0a593bc257b1
   /**
    * @return Reservation
    */
    public function get(string $id) : array
    {
        $endpoint = "/api/v1/reservations/" . $id;


        $response = $this->client->request('GET', $endpoint,['json' => $this->parseAuthParams($this->authParams)]);
        if($response->getStatusCode()!=200)
          return [];
        
        $responseString = $response->getBody();
        $responseObject = json_decode($responseString);
        $reservationsArray = [];
        foreach($responseObject->data as $reservationRaw)
        {
            $reservationsArray[] = self::parseReservation($reservationRaw);
        }

        return $reservationsArray;

    }

    public function parseReservation(object $reservationRaw) : Reservation
    {
        return ReservationMapper::mapReservation($reservationRaw);
    }
}