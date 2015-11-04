@extends('layouts.master')

@section('title','Stations')

@section('content')
<table class="table">
    <tr>
        <tr>
            <td>Id</td>
            <td>{{ $station->id }}</td>
        </tr>
        <tr>
            <td>Name</td>
            <td>{{ $station->name }}</td>
        </tr>
        <tr>
            <td>Country</td>
            <td>{{ $station->country }}</td>
        </tr>
        <tr>
            <td>Latitude</td>
            <td>{{ $station->latitude }}</td>
        </tr>
        <tr>
            <td>Longitude</td>
            <td>{{ $station->longitude }}</td>
        </tr>
        <tr>
            <td>Elevation</td>
            <td>{{ $station->elevation }}</td>
        </tr>
    </tr>
</table>

@endsection
