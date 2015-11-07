@extends('layouts.master')

@section('master_content')
    <div class="container">
        @if (session('status'))
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            </div>
        </div>
        @endif
        @if (session('error'))
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger">
                {{ session('error') }}
                </div>
            </div>
        </div>
        @endif
        @yield('content')
    </div>
@endsection
