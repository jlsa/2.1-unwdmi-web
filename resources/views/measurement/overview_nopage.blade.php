@extends('layouts.page')

@section('title','Measurements')

@section('content')
<div class="row">
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th>station_id</th>
                    <th>time</th>
                    <th>temperature</th>
                    <th>dew_point</th>
                    <th>station_pressure</th>
                    <th>sea_level_pressure</th>
                    <th>visibility</th>
                    <th>precipitation</th>
                    <th>snow_depth</th>
                    <th>events</th>
                    <th>cloud_cover</th>
                    <th>wind_direction</th>
                    <th>wind_speed</th>
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
