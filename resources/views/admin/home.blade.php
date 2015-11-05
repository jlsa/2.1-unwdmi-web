@extends('layouts.page')

@section('content')
    <div class="container">
        <div class="title">
        <h1>Administration Panel</h1>
        </div>
        <div class="content">
            @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
            @endif
            <a href="{{ action('Admin\AdminPanelController@showCreateUser') }}">Create user account</a>
        </div>
    </div>
@endsection
