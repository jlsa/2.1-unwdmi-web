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
    public function showPerStation(Request $request)
    {
        $station = Station::find($request->input('station'));
        return [
            'station' => $station,
            'measurements' => $station->measurements()
                ->where('time', '>=', Carbon::now()->subDay())
                ->get()
        ];
    }

    /**
     * Returns stationName, stationId, longitude, latitude and most recent precipitation
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
