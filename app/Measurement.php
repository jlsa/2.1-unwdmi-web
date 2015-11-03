<?php

namespace Leertaak5;

use Illuminate\Database\Eloquent\Model;
use Leertaak5\Station;

class Measurement extends Model
{
    public $timestamps = false;

    public function station()
    {
        return $this->belongsTo('Leertaak5\Station', 'stn');
    }
}
