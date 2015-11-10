@extends('layouts.page')

@section('title','Stations')

@section('content')

<div class="page-title m-t">
    <h1>
        {{ title_case($station->name) }},
        {{ title_case($station->country) }}
    </h1>
</div>

<div class="row">
    <div class="col-sm-4">
        <table class="table table-striped table-bordered">
            <tbody>
                <tr>
                    <th class="col-md-4 text-right">Id:</th>
                    <td class="col-md-8">{{ $station->id }}</td>
                </tr>
                <tr>
                    <th class="col-md-4 text-right">Name:</th>
                    <td class="col-md-8">{{ title_case($station->name) }}</td>
                </tr>
                <tr>
                    <th class="col-md-4 text-right">Country:</th>
                    <td class="col-md-8">{{ title_case($station->country) }}</td>
                </tr>
                <tr>
                    <th class="col-md-4 text-right">Latitude:</th>
                    <td class="col-md-8">{{ $station->latitude }}°N</td>
                </tr>
                <tr>
                    <th class="col-md-4 text-right">Longitude:</th>
                    <td class="col-md-8">{{ $station->longitude }}°E</td>
                </tr>
                <tr>
                    <th class="col-md-4 text-right">Elevation:</th>
                    <td class="col-md-8">{{ $station->elevation }}m</td>
                </tr>
                <tr>
                    <th class="col-md-4 text-right"></th>
                    <td class="col-md-8"><a class="btn btn-primary" href="{{action('StationsController@showMeasurements', $station)}}" role="button">Measurements</a></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-sm-8"
         style="height: 300px;">
        <span data-insert="map"
              data-lat="{{ $station->latitude }}"
              data-lon="{{ $station->longitude }}"
              data-zoom="9"></span>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="chart"
             data-insert="chart"
             data-station-id="{{ $station->id }}"
             data-title="Rainfall near {{ title_case($station->name) }}, {{ title_case($station->country) }}"
             data-subtitle="over the past 24 hours"
             style="height: 480px"></div>
    </div>
</div>

@endsection
