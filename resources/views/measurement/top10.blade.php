@inject('events', 'Leertaak5\Services\EventsRenderer')

@extends('layouts.page')

@section('title','Measurements')

@section('content')
<div class="row m-t">
    <div class="col-md-12">
    <h1>Temperatures Kyoto</h1>
    On this page you can find the top ten temperatures on the same longitude as Kyoto.
    </div>
</div>

<div class="row">
    <div class="col-md-12 m-t-md">
        <table class="table table-bordered table-striped">
            <thead class="header_rotate">
                <tr>
                    <th><div>Station</div></th>
                    <th><div>Measurement id</div></th>
                    <th><div>Time</div></th>
                    <th><div>Temperature</div></th>
                    <th><div>Dew point</div></th>
                </tr>
            </thead>
            @foreach ($measurements as $measurement)
            <tr>
                <td>
                    <a href="{{ action('StationsController@show', $measurement->station_id) }}">
                        {{ title_case($measurement->station->country) }} - {{ title_case($measurement->station->name) }}
                    </a>
                </td>
                <td>
                    <a href="{{ action('MeasurementsController@show', $measurement->id) }}">
                        {{ $measurement->id}}
                    </a>
                </td>
                <td>
                    {{ $measurement->time }}
                </td>
                <td>
                    {{ number_format($measurement->temperature, 1) }}°C
                </td>
                <td>
                    {{ number_format($measurement->dew_point, 1) }}°C
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection
