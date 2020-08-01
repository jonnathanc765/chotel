<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Reservation;
use App\Room;
use App\Traits\RoomUtilities;
use Carbon\Carbon;
use Illuminate\Http\Request;
class ReservationController extends Controller
{
    use RoomUtilities;

    public function index(Request $request)
    {
        $reservations = Reservation::with(['room.type'])->orderBy('created_at', 'DESC')->get();

        return response()->json(['reservations' => $reservations]);
    }

    public function store(CreateReservationRequest $request)
    {
        $request->validated();

        $room = $this->getAvalaibleRoom($request->type_id, $request->checkin, $request->checkout);

        if (!$room) {
            return response()->json([
                'msg' => 'This room it is not avalaible right now, try later.'
            ], 302);
        }

        $from = Carbon::parse($request->checkin)->format('Y-m-d');
        $to = Carbon::parse($request->checkout)->format('Y-m-d');

        $reservation = Reservation::create([
            'check_in' => $from,
            'check_out' => $to,
            'room_id' => $room->id
        ]);

        return response()->json([
            'reservation'   => $reservation,
            'msg'           => 'The reservation was maked successfully'
        ], 201);
    }
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $request->validated();

        $room = $this->getAvalaibleRoom($request->type_id, $request->checkin, $request->checkout);

        if (!$room) {
            return response()->json([
                'msg' => 'This room it is not avalaible right now, try later.'
            ], 302);
        }

        $from = Carbon::parse($request->checkin)->format('Y-m-d');
        $to = Carbon::parse($request->checkout)->format('Y-m-d');

        $reservation->update([
            'check_in'      => $from,
            'check_out'     => $to,
            'room_id'       => $room->id
        ]);

        return response()->json([
            'reservation'   => $reservation,
            'msg'           => 'The reservation was updated successfully'
        ]);
    }
    public function destroy(Reservation $reservation)
    {

        $reservation->delete();

        return response()->json([
            'msg' => 'The reservation was deleted successfully.'
        ]);
    }
}
