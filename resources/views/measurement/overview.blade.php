@inject('events', 'Leertaak5\Services\EventsRenderer')

@extends('layouts.page')

@section('title','Measurements')

@section('content')
<div class="row m-t">
    <div class="col-md-12">
        {!! $measurements->render() !!}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-striped">
            <thead class="header_rotate">
                <tr>
                    <th><div>Station</div></th>
                    <th><div>Time</div></th>
                    <th><div>Temperature</div></th>
                    <th><div>Dew point</div></th>
                    <th><div>Station Pressure</div></th>
                    <th><div>Sea Level Pressure</div></th>
                    <th><div>Visibility</div></th>
                    <th><div>Precipitation</div></th>
                    <th><div>Snow Depth</div></th>
                    <th><div>Events</div></th>
                    <th><div>Cloud Cover</div></th>
                    <th><div>Wind Direction</div></th>
                    <th><div>Wind Speed</div></th>
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
                    {{ $measurement->time }}
                </td>
                <td>
                    {{ number_format($measurement->temperature, 1) }}°C
                </td>
                <td>
                    {{ number_format($measurement->dew_point, 1) }}°C
                </td>
                <td>
                    {{ number_format($measurement->station_pressure, 1) }}mbar
                </td>
                <td>
                    {{ number_format($measurement->sea_level_pressure, 1) }}mbar
                </td>
                <td>
                    {{ number_format($measurement->visibility, 1) }}km
                </td>
                <td>
                    {{ number_format($measurement->precipitation, 2) }}cm
                </td>
                <td>
                    {{ number_format($measurement->snow_depth, 1) }}cm
                </td>
                <td>
                    {!! $events->render($measurement->events) !!}
                </td>
                <td>
                    {{ number_format($measurement->cloud_cover, 1) }}%
                </td>
                <td>
                    {{ round($measurement->wind_direction) }}°
                </td>
                <td>
                    {{ number_format($measurement->wind_speed, 1) }}km/h
                </td>
            </tr>
            @endforeach
        </table>
        {!! $measurements->render() !!}
    </div>
</div>
@endsection
