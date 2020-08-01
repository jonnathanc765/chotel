<?php

namespace App\Traits;

use App\Room;

trait RoomUtilities {

    function getAvalaibleRoom($roomTypeId, $checkin, $checkout)
    {
        $room = Room::byType($roomTypeId)
        ->whereDoesntHave('reservations', function ($query) use ($checkin, $checkout)
        {
            $query
                ->whereDate('check_in', '<', $checkout)
                ->whereDate('check_out', '>', $checkin)
            ;
        })
        ->orderBy('id', 'ASC')
        ->first();

        return $room;
    }

}
