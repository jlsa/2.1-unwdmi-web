@extends('layouts.map')

@section('title', 'Rainfall')

@section('content')

  <span data-insert="map"
        data-heat-map="precipitation"
        data-zoom="2"></span>

@endsection('content')
