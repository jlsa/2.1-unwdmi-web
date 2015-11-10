@extends('layouts.page')

@section('content')
<div class="row m-t">
    <div class="col-md-12">
        <h1>Administration Panel</h1>
    </div>
</div>
@if (count($errors) > 0)
<div class="row m-t">
    <div class="col-md-12">
     <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
</div>
@endif
<form action="{{ action('Admin\AdminPanelController@create') }}" method="post">
    {!! csrf_field() !!}
    Name:
    <input class="form-control" type="text" name="name" value="{{ old('name') }}">
    <br/>
    E-mail:
    <input class="form-control" type="email" name="email" value="{{ old('email') }}">
    <br/>
    Password:
    <input class="form-control" type="password" name="pass"/>
    <br/>
    Admin:
    <input @if(old('rights') >0) checked @endif class="form-control" type="checkbox" name="rights" value="1" />
    <input type="submit" value="Submit"/>
</form>
</div>
</div>
@endsection
