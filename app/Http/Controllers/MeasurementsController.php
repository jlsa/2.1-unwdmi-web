<?php

namespace Leertaak5\Http\Controllers;

use Illuminate\Http\Request;

use Leertaak5\Measurement;
use Leertaak5\Http\Requests;
use Leertaak5\Http\Controllers\Controller;

class MeasurementsController extends Controller
{
    public function show($id)
    {
        $measurement = Measurement::find($id);
        return view('measurement.view', [
            'measurement' =>  $measurement
        ]);
    }

    public function index()
    {
        $measurements = Measurement::paginate(30);
        return view('measurement.overview', [
            'measurements' => $measurements
        ]);

    }
}
