@extends('layouts.page')

@section('title','Measurement')

@section('content')
<table class="table">
    <tr>
        <td>station_id</td>
        <td>
            <a href="{{ action('StationsController@show', $measurement->station_id) }}">
                {{ $measurement->station_id }}
            </a>
        </td>
    </tr>
    <tr>
        <td>time</td>
        <td>{{ $measurement->time }}</td>
    </tr>
    <tr>
        <td>temperature</td>
        <td>{{ $measurement->temperature }}</td>
    </tr>
    <tr>
        <td>dew_point</td>
        <td>{{ $measurement->dew_point }}</td>
    </tr>
    <tr>
        <td>station_pressure</td>
        <td>{{ $measurement->station_pressure }}</td>
    </tr>
    <tr>
        <td>sea_level_pressure</td>
        <td>{{ $measurement->sea_level_pressure }}</td>
    </tr>
    <tr>
        <td>visibility</td>
        <td>{{ $measurement->visibility }}</td>
    </tr>
    <tr>
        <td>precipitation</td>
        <td>{{ $measurement->precipitation }}</td>
    </tr>
    <tr>
        <td>snow_depth</td>
        <td>{{ $measurement->snow_depth }}</td>
    </tr>
    <tr>
        <td>events</td>
        <td>{{ $measurement->events }}</td>
    </tr>
    <tr>
        <td>cloud_cover</td>
        <td>{{ $measurement->cloud_cover }}</td>
    </tr>
    <tr>
        <td>wind_direction</td>
        <td>{{ $measurement->wind_direction }}</td>
    </tr>
    <tr>
        <td>wind_speed</td>
        <td>{{ $measurement->wind_speed }}</td>
    </tr>
</table>

@endsection
