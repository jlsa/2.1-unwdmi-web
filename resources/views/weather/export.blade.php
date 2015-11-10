@extends('layouts.page')

@section('title','Export')

@section('content')

<div class="row m-t">

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @else
    <div class="alert alert-info" role="alert">
        Please select the criteria by which you would like to filter the raw data.
    </div>
    @endif

    <form method="post" action="{{action('DownloadController@download')}}">
        {!! csrf_field() !!}
        <div class="row">

            <div class="col-sm-6 form-group">
                <h2>Station name</h2>
                <div class="form-group row">
                    <label class="col-sm-2">Filter by:</label>
                    <div class="col-sm-4 checkbox">
                        <label>
                            <input type="checkbox" name="show[]" value="name" id="showname">
                        </label>
                    </div>
                </div>
                <fieldset class="form-group">
                    <select multiple class="form-control" name="stationSelect" id="stationSelect">
                        @foreach($stations as $station)
                        <option value="{{$station->id}}">{{title_case($station->name)}}</option>
                        @endforeach
                    </select>
                </fieldset>
            </div>


            <div class="col-sm-6 form-group">
            <h2>Country</h2>
                <div class="form-group row">
                    <label class="col-sm-2">Filter by:</label>
                    <div class="col-sm-4 checkbox">
                        <label>
                            <input type="checkbox" name="show[]" value="country" id="showcountry">
                        </label>
                    </div>
                </div>
                <fieldset class="form-group">
                    <select multiple class="form-control" name="countrySelect" id="countrySelect">
                        @foreach($countries as $country)
                        <option value="{{$country}}">{{title_case($country)}}</option>
                        @endforeach
                    </select>
                </fieldset>
            </div>

        </div>

        <hr>

        <div class="row form-groupcol-sm-12">
        @foreach($fields['showOnlyFields'] as $showOnyField)
            <div class="form-group col-sm-6">
                <h3>{{ title_case(str_replace("_"," ",$showOnyField)) }}</h3>

                <div class="row">
                    <label class="col-sm-3">Include:</label>
                    <div class="col-sm-8 checkbox">
                        <input type="checkbox" name="show[{{ $showOnyField }}" id="show{{ $showOnyField }}">
                    </div>
                </div>
                
            </div>
        @endforeach
        </div>

        <hr>

        <div class="row">
        @foreach($fields['numberFields'] as $numberField)
        <div class="form-group col-sm-6">
            <h3>{{ title_case(str_replace("_"," ",$numberField)) }}</h3>

            <div class="row">
                <label class="col-sm-3">Filter by:</label>
                <div class="col-sm-8 checkbox">
                    <input type="checkbox" name="show[]" value="{{ $numberField }}" id="show{{ $numberField }}">
                </div>
            </div>

            <div class="row">
                <fieldset>
                    <label class="col-md-3"for="{{ $numberField }}-min">Minimum:</label>
                    <input class="col-md-8" 
                        @if ($numberField == 'time')
                        placeholder="YYYY-mm-dd" 
                        @else
                        placeholder="{{ $fieldValues[$numberField]['min'] }}"
                        @endif
                        type="text" name="filter[{{ $numberField }}][min]" id="show{{ $numberField }}-min" class="form-control">
                </fieldset>
                <fieldset>
                    <label class="col-md-3" for="{{ $numberField }}-max">Maximum:</label>
                    <input class="col-md-8" 
                        @if ($numberField == 'time')
                        placeholder="YYYY-mm-dd" 
                        @else
                        placeholder="{{ $fieldValues[$numberField]['max'] }}" 
                        @endif
                        type="text" name="filter[{{ $numberField }}][max]" id="show{{ $numberField }}-max" class="form-control">
                </fieldset>
            </div>

        </div>
        @endforeach
        </div>

        <div class="row">
            <button type="submit" class="btn btn-primary">Download</button>
        </div>
    </form>
</div>
@endsection('content')
