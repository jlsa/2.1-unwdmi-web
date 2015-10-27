<?php

namespace Leertaak5\Http\Controllers;

use Illuminate\Http\Request;
use Leertaak5\Http\Requests;
use Leertaak5\Http\Controllers\Controller;
use Carbon\Carbon;
use Leertaak5\Measurement;

class Top10TemperatureController extends Controller
{
    /**
     * Shows the top 10 temperatures for the past 24 hours
     * within the longitude range of Kyoto.
     */
    public function show()
    {
        //Grab all Measurements, where time is of the last
        //24 hrs and the longitude = Kyoto's

        //Kyoto's longitude is 135.733 in decimals
        $longitude = 135.733;

        $measurements = Measurement::where('longitude', $longitude)
                        ->where('created_at', '>=', Carbon::now()->subDay())
                        ->orderBy('temperate', 'desc')
                        ->take(10)
                        ->get();
    }
}
