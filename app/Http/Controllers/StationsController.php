<?php

namespace Leertaak5\Http\Controllers;

use Illuminate\Http\Request;

use Leertaak5\Station;
use Leertaak5\Http\Requests;
use Leertaak5\Http\Controllers\Controller;

class StationsController extends Controller
{
    public function show($id)
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
        return view('station.view',[
                    'station' => $station
                ]);
    }

    public function index()
    {
        $stations = Station::all();
        return view('station.overview',[
            'stations' => $stations
        ]);
    }
}
