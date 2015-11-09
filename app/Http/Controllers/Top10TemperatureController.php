<?php

namespace Leertaak5\Http\Controllers;

use Illuminate\Http\Request;
use Leertaak5\Http\Requests;
use Leertaak5\Http\Controllers\Controller;
use Carbon\Carbon;
use Leertaak5\Measurement;
use Leertaak5\Station;

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

        $data = array();

        //Kyoto's longitude is 135.733 in decimals
        $longitude = 135.733;

        $stations = Station::where('longitude', $longitude)->get();

        //TODO: remove this after demoderp. Client wants a little margin on the longitude
        //$stations = Station::whereBetween('longitude', [135.4, 136])->get();

            $measurements = Measurement::with('station')
                            ->where('time', '>=', Carbon::now()->subDay())
                            ->orderBy('temperature', 'desc')
                            ->whereHas('station', function ($sql) use ($longitude) {
                                    $sql->where('longitude', '=', $longitude);
                            })
                            ->take(10)
                            ->get();


        return view('measurement.overview_nopage', [
            'measurements'=> $measurements]);
    }
}
