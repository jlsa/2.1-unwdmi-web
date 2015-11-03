<?php

namespace Leertaak5\Http\Controllers;

use Illuminate\Http\Request;
use Leertaak5\Http\Requests;
use Leertaak5\Http\Controllers\Controller;
use Leertaak5\Station;
use Leertaak5\Measurement;

/**
 * Returns an array, indexed by station numbers,
 * containing all rainfall data per station.
 */

class RainfallController extends Controller
{
    /**
     * Returns most recent longitude, latitude and precipation
     * per location.
     */
    public function showPerStation()
    {
        $data = array();

        $stations = Station::all();

        foreach ($stations as $station) {
            $measurements = Measurement::with('station')
            ->groupBy('stn')
            ->groupBy('id')
            ->orderBy('timestamp', 'desc')
            ->take(1)
            ->get();

            foreach ($measurements as $measurement) {
                $data[$station->id] = array('station' => $station, 'precipation' => $measurement->prcp);
            }
        }

        return $data;
    }
}
