@extends('layouts.page')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Log in</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        @if($errors->count() > 0)
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    {{ $error }}<br />
                @endforeach
            </div>
        @endif
        
        <form method="POST" action('Auth\AuthController@getLogin')>
            {!! csrf_field() !!}
            <fieldset class="form-group">
                <label for="email">Email</label>
                <input class="form-control" type="email" name="email" value="{{ old('email') }}">
            </fieldset>


            <fieldset class="form-group">
                <label for="password">Password</label>
                <input class="form-control" type="password" name="password" id="password">
            </fieldset>

            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember"> Remember Me
                </label>
            </div>

            <div>
                <button class="btn btn-primary" type="submit">Login</button>
            </div>
        </form>
    </div>
</div>

@endsection('content')
