@extends('layouts.page')

@section('title','Export')

@section('content')
<div class="row m-t">
    <form method="post" action="{{action('DownloadController@download')}}">
         {!! csrf_field() !!}
        <h2>Station name</h2>
                <div class="form-group row">
                    <label class="col-sm-2">Show:</label>
                    <div class="col-sm-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="show[]" value="name" id="showname">
                            </label>
                        </div>
                    </div>
                </div>
                <fieldset class="form-group">
                    <label for="stationSelect">Select:</label>
                    <select multiple class="form-control" name="stationSelect" id="stationSelect">
                        @foreach($stations as $station)
                        <option value="$station->id">{{title_case($station->name)}}</option>
                        @endforeach
                    </select>
                </fieldset>


        <h2>Country</h2>
                <div class="form-group row">
                    <label class="col-sm-2">Show:</label>
                    <div class="col-sm-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="show[]" value="country" id="showcountry">
                            </label>
                        </div>
                    </div>
                </div>
                <fieldset class="form-group">
                    <label for="countrySelect">Select</label>
                    <select multiple class="form-control" name="stationSelect" id="countrySelect">
                        @foreach($countries as $country)
                        <option value="$country">{{title_case($country)}}</option>
                        @endforeach
                    </select>
                </fieldset>


        @foreach($fields['showOnlyFields'] as $showOnyField)
        <h2>{{ title_case(str_replace("_"," ",$showOnyField)) }}</h2>
                <div class="form-group row">
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
        <div class="form-group row">
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
