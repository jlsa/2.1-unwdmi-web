@extends('layouts.master')

@section('title','Stations')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
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
                    {{ $station->name }}
                </td>
                <td>
                    {{ $station->country }}
                </td>
                <td>
                    {{ $station->latitude }}
                </td>
                <td>
                    {{ $station->longitude }}
                </td>
                <td>
                    {{ $station->elevation }}
                </td>
            </tr>
        @endforeach
    </table>

@endsection
