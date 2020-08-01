<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['check_in', 'check_out', 'room_id'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
