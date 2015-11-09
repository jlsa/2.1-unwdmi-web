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
    public function show(Request $request)
    {
        $scope = $request->input('scope', null);
        $longitude = 135.733;

        $stations = Station::where('longitude', $longitude)
            ->select('id', 'name', 'country')
            ->with(['measurements' => function ($query) use ($scope) {
                $query->where('time', '>=', Carbon::now()->subWeek())
                      ->where('temperature', '<', 20)
                      ->orderBy('time', 'desc');

                if ($scope) {
                    $query->select($scope);
                    $query->addSelect('station_id');
                }
            }])
            ->get();

        $measurements = $stations
            ->pluck('measurements')
            ->flatten();

        return [
            'stations' => $stations->map(function ($station) {
                return [
                    'id' => $station->id,
                    'name' => $station->name,
                    'country' => $station->country
                ];
            }),
            'measurements' => $measurements
        ];
    }
}
