@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="title">
            <h1>Administration Panel</h1>
        </div>
        <div class="content">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
            <form action="{{ action('Admin\AdminPanelController@createUser') }}" method="post">
            {!! csrf_field() !!}
                Name:
                <input class="form-control" type="text" name="name"/>
                <br/>
                E-mail:
                <input class="form-control" type="email" name="email"/>
                <br/>
                Password:
                <input class="form-control" type="password" name="pass"/>
                <br/>
                Admin:
                <input class="form-control" type="checkbox" name="rights"/>
                <input type="submit" value="JULLIE ZIJN SCRUBS xx Jari <3"/>
            </form>
        </div>
    </div>
@endsection
