<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public function reservations()
    {
        return  $this->hasMany(Reservation::class);
    }
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    // Query Scopes

    public function scopeByType($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('type_id', $keyword);
        }
    }
}
