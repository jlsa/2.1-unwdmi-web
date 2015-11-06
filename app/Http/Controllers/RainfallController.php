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
    public function index()
    {
        return view('weather.rainfall');
    }

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
     * Returns most recent longitude, latitude and precipitation
     * per location.
     */
    public function showMostRecent()
    {
        $maxTemp = Measurement::select(
            'station_id',
            DB::raw('MAX(time) as time')
        )->groupBy('station_id');


        $measurements = Measurement::join(
            DB::raw('('.$maxTemp->toSql().') "maxTime"'),
            function ($join) {
                $join->on('maxTime.station_id', '=', 'measurements.station_id');
                $join->on('maxTime.time', '=', 'measurements.time');
            }
        )->with('station')->get();

        foreach ($measurements as $measurement) {
            $station = $measurement->station;
            $data[] = array(
                'id' => $station->id,
                'name' => $station->name,
                'latitude' => $station->latitude,
                'longitude' => $station->longitude,
                'precipitation' => $measurement->precipitation
            );
        }
        return $data;
    }
}
