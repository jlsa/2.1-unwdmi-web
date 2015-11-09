<?php

namespace Leertaak5\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use Leertaak5\Measurement;
use Leertaak5\Station;
use Leertaak5\Http\Requests;
use Leertaak5\Http\Controllers\Controller;

class MeasurementsController extends Controller
{

    /**
     * Show all the measurements in pages
     */

    public function index()
    {
        $measurements = Measurement::paginate(30);
        return view('measurement.overview', [
            'measurements' => $measurements
        ]);

    }

    /**
     * Shows one measurement with the given
     * @param $id The ID
     */
    public function show($id)
    {
        $measurement = Measurement::find($id);
        return view('measurement.view', [
            'measurement' =>  $measurement
        ]);
    }

    /**
     * Shows the top 10 temperatures for the past 24 hours
     * within the longitude range of Kyoto.
     */
    public function top10()
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


        return view('measurement.top10', [
            'measurements'=> $measurements]);
    }

    /**
     * Shows all the weatherdata of stations on the same
     * longitude as Kyoto. The data displayed is one week
     * old. The data should only be shown if the temperature
     * is below 20 degrees Celsius.
     */
    public function kyotoLongitude(Request $request)
    {
        $longitude = 135.733;

        $measurements = Measurement::with('station')
            ->where('time', '>=', Carbon::now()->subWeek())
            ->where('temperature', '<', 20)
            ->whereHas('station', function ($sql) use ($longitude) {
                $sql->where('longitude', '=', $longitude);
            })
            ->orderBy('time', 'desc');

        return view('measurement.overview', [
            'measurements' => $measurements->paginate(50)
        ]);
    }


}
