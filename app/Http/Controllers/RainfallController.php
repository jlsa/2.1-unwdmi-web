<?php

namespace Leertaak5\Http\Controllers;

use Illuminate\Http\Request;
use Leertaak5\Http\Requests;
use Leertaak5\Http\Controllers\Controller;
use Leertaak5\Station;
use Leertaak5\Measurement;
use Carbon\Carbon;

/**
 * Returns an array, indexed by station numbers,
 * containing all rainfall data per station.
 */

class RainfallController extends Controller
{
    /**
     * Displays information per station of the last
     * 24 hours.
     */
    public function showPerStation()
    {
        $data = array();

        $stationid = 1;

        $station = Station::where('id', '=', $stationid)
        ->take(1)
        ->get();

        $data['station'] = $station;
        $data['measurements'] = array();

        $measurements = Measurement::with('station')
        ->where('time', '>=', Carbon::now()->subDay())
        ->chunk(1000, function ($measurements) use (&$data) {
            foreach ($measurements as $measurement) {
                $data['measurements'][] = $measurement;
            }
        });

        return $data;
    }

    /**
     * Returns most recent longitude, latitude and precipation
     * per location.
     */
    public function showMostRecent()
    {
        $data = array();

        $stations = Station::all();

        foreach ($stations as $station) {
            $measurements = Measurement::with('station')
            ->groupBy('station_id')
            ->groupBy('id')
            ->orderBy('time', 'desc')
            ->take(1)
            ->get();

            foreach ($measurements as $measurement) {
                $data[$station->id] = array('station' => $station, 'precipation' => $measurement->precipation);
            }
        }

        return $data;
    }
}
