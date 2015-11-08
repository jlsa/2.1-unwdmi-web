@extends('layouts.page')

@section('title','Measurements')

@section('content')
<div class="row m-t-md">
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
                        {{ $measurement->station_id }}
                    </a>
                </td>
                <td>
                    {{ $measurement->time }}
                </td>
                <td>
                    {{ $measurement->temperature }}
                </td>
                <td>
                    {{ $measurement->dew_point }}
                </td>
                <td>
                    {{ $measurement->station_pressure }}
                </td>
                <td>
                    {{ $measurement->sea_level_pressure }}
                </td>
                <td>
                    {{ $measurement->visibility }}
                </td>
                <td>
                    {{ $measurement->precipitation }}
                </td>
                <td>
                    {{ $measurement->snow_depth }}
                </td>
                <td>
                    {{ $measurement->events }}
                </td>
                <td>
                    {{ $measurement->cloud_cover }}
                </td>
                <td>
                    {{ $measurement->wind_direction }}
                </td>
                <td>
                    {{ $measurement->wind_speed }}
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection
