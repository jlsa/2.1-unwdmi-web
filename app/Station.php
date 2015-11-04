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
}
