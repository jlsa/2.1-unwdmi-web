@extends('layouts.page')

@section('title','Stations')

@section('content')
<div class="row m-t">
    <div class="col-md-12">
        {!! $stations->render() !!}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Elevation</th>
                </tr>
            </thead>
            @foreach ($stations as $station)
            <tr>
                <td>
                    <a href="{{ action('StationsController@show', $station) }}">
                        {{ $station->id }}
                    </a>
                </td>
                <td>
                    {{ title_case($station->name) }}
                </td>
                <td>
                    {{ title_case($station->country) }}
                </td>
                <td>
                    {{ $station->latitude_str }}
                </td>
                <td>
                    {{ $station->longitude_str }}
                </td>
                <td>
                    {{ $station->elevation }}
                </td>
            </tr>
            @endforeach
        </table>
        {!! $stations->render() !!}
    </div>
</div>
@endsection
