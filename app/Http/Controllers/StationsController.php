<?php

namespace Leertaak5\Http\Controllers;

use Illuminate\Http\Request;

use Leertaak5\Station;
use Leertaak5\Http\Requests;
use Leertaak5\Http\Controllers\Controller;

class StationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function jsonIndex()
    {
        return Station::select([ 'id', 'name', 'latitude', 'longitude' ])->get();
    }

    public function jsonShow($id)
    {
        $station = Station::find($id);
        return [
            'station' => $station,
            'measurement' => $station->measurements()
                ->orderBy('time', 'desc')
                ->first()
        ];
    }

    public function show($id)
    {
        $station = Station::find($id);
        return view('station.view', [
            'station' => $station
        ]);
    }

    public function index()
    {
        $stations = Station::paginate(30);
        return view('station.overview', [
            'stations' => $stations
        ]);
    }

    public function showMeasurements($id)
    {
        $measurements = Measurement::where('station_id', $id);
        return view('measurement.overview', [
            'measurements' =>  $measurements->paginate(30)
        ]);
    }
}
