@inject('events', 'Leertaak5\Services\EventsRenderer')

@extends('layouts.page')

@section('title','Measurements')

@section('content')
<div class="row">
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
                    {!! $events->render($measurement->events) !!}
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
        {!! $measurements->render() !!}
    </div>
</div>
@endsection
