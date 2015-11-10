<?php

namespace Leertaak5;

use Illuminate\Database\Eloquent\Model;
use Leertaak5\Measurement;

class Station extends Model
{
    public function measurements()
    {
        return $this->hasMany('Leertaak5\Measurement');
    }

    public function getLatitudeStrAttribute()
    {
        $long = $this->attributes['latitude'];
        return abs($long) . ($long < 0 ? '°S' : '°N');
    }

    public function getLongitudeStrAttribute()
    {
        $long = $this->attributes['longitude'];
        return abs($long) . ($long < 0 ? '°W' : '°E');
    }
}
