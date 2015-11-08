@extends('layouts.page')

@section('title','Export')

@section('content')
    <form method="get" action()>
        @foreach($fields['numberFields'] as $numberField)
            <div class="card">
                <div class="card-header">{{ $numberField }}</div>
                <div class="card-block">
                    <div class="form-group">
                        <label for="show{{ $numberField }}">show: </label>
                        <input type="checkbox" name="show[]" value="{{ $numberField }}" id="show{{ $numberField }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="{{ $numberField }}-min">minimum: </label>
                        <input type="text" name="filter[{{ $numberField }}][min]]" id="show{{ $numberField }}-min" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="{{ $numberField }}-max">maximum: </label>
                        <input type="text" name="filter[{{ $numberField }}][max]]" id="show{{ $numberField }}-max" class="form-control">
                    </div>
                </div>
            </div>
        @endforeach

        @foreach($fields['nameFields'] as $nameField)
            <div class="card">
                <div class="card-header">{{ $nameField }}</div>
                <div class="card-block">
                    <div class="form-group">
                        <label for="show{{ $nameField }}">show: </label>
                        <input type="checkbox" name="show[]" value="{{ $nameField }}" id="show{{ $nameField }}" class="form-control">
                    </div>
                </div>
            </div>
        @endforeach

        @foreach($fields['showOnlyFields'] as $showOnyField)
            <div class="card">
                <div class="card-header">{{ $showOnyField }}</div>
                <div class="card-block">
                    <div class="form-group">
                        <label for="show{{ $showOnyField }}">show</label>
                        <input type="checkbox" name="show[{{ $showOnyField }}" id="show{{ $showOnyField }}">
                    </div>
                </div>
            </div>
        @endforeach
        <button type="submit" class="btn btn-primary form-control">submit</button>
    </form>
@endsection('content')
