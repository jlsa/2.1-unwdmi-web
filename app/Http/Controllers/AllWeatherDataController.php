<?php

namespace Leertaak5\Http\Controllers;

use Illuminate\Http\Request;
use Leertaak5\Http\Requests;
use Leertaak5\Http\Controllers\Controller;
use Carbon\Carbon;
use Leertaak5\Measurement;
use Leertaak5\Station;

class AllWeatherDataController extends Controller
{
    /**
     * Shows all the weatherdata of stations on the same
     * longitude as Kyoto. The data displayed is one week
     * old. The data should only be shown if the temperature
     * is below 20 degrees Celsius.
     */
    public function show()
    {
        $data = array();

        $longitude = 135.733;

        $stations = Station::where('longitude', $longitude)->get();

        foreach ($stations as $station) {
            $measurements = Measurement::with('station')
                            ->where('time', '>=', Carbon::now()->subWeek())
                            ->where('temperature', '<', 20)
                            ->groupBy('station_id')
                            ->groupBy('id')
                            ->get();

            foreach ($measurements as $measurement) {
                $data[$station->id][] = $measurement;
            }
        }

        return $data;
    }
}
