@extends('layouts.page')

@section('content')
    @if (session('error'))
        <div class="alert alert-danger">
        {{ session('error') }}
        </div>
    @endif
    <div class="title">Laravel 5</div>
@endsection
