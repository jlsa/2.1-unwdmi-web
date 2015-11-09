@extends('layouts.page')

@section('title','Export')

@section('content')
<div class="row m-t">
    <form method="get" action="{{action('DownloadController@download')}}">

        @foreach($fields['nameFields'] as $nameField)
        <h2>{{ title_case(str_replace("_"," ",$nameField)) }}</h2>
                <div class="form-group row inline-form">
                    <label class="col-sm-2">Show:</label>
                    <div class="col-sm-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="show[]" value="{{ $nameField }}" id="show{{ $nameField }}">
                            </label>
                        </div>
                    </div>
                </div>
        @endforeach


        @foreach($fields['showOnlyFields'] as $showOnyField)
        <h2>{{ title_case(str_replace("_"," ",$showOnyField)) }}</h2>
                <div class="form-group row inline-form">
                    <label class="col-sm-2">Show:</label>
                    <div class="col-sm-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="show[{{ $showOnyField }}" id="show{{ $showOnyField }}">
                            </label>
                        </div>
                    </div>
                </div>
        @endforeach


        @foreach($fields['numberFields'] as $numberField)
        <h3>{{ title_case(str_replace("_"," ",$numberField)) }}</h3>
        <div class="form-group row inline-form">
            <label class="col-sm-2">Show:</label>
            <div class="col-sm-10">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="show[]" value="{{ $numberField }}" id="show{{ $numberField }}">
                    </label>
                </div>
            </div>
        </div>
        <fieldset class="form-group row">
            <label class="col-md-2" for="{{ $numberField }}-min">minimum: </label>
            <input class="col-md-4" type="text" name="filter[{{ $numberField }}][min]" id="show{{ $numberField }}-min" class="form-control">
        </fieldset>
        <fieldset class="form-group row">
            <label class="col-md-2" for="{{ $numberField }}-max">maximum: </label>
            <input class="col-md-4" type="text" name="filter[{{ $numberField }}][max]" id="show{{ $numberField }}-max" class="form-control">
        </fieldset>
        @endforeach


        <button type="submit" class="btn btn-primary">submit</button>
    </form>
</div>
@endsection('content')
