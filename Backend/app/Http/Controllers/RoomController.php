<?php

namespace App\Http\Controllers;

use App\Reservation;
use App\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request, $roomTypeId, $checkin, $checkout)
    {

        $checkin = Carbon::parse($checkin);
        $checkout = Carbon::parse($checkout);

        $rooms = Room::byType($roomTypeId)
        ->with(['type', 'reservations'])
        ->whereDoesntHave('reservations', function ($query) use ($checkin, $checkout)
        {
            $query
                ->whereDate('check_in', '<', $checkout)
                ->whereDate('check_out', '>', $checkin)
            ;
        })
        ->get()
        ->groupBy(function ($room)
        {
            return $room->type->description;
        })
        ->map(function ($room)
        {
            return $room->count();
        })
        ;

        return response()->json([
            'avalaible' => $rooms->sum()
        ]);

    }
}
