<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>Weather Data Tool - Kyoto University - @yield('title')</title>

    <link rel="stylesheet" href="{{ asset('style/app.css') }}">
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <a class="navbar-brand" href="{{ action('PagesController@index') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Kyoto University">
        </a>
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ action('MeasurementsController@top10') }}">Temperatures</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ action('RainfallController@index') }}">Rainfall</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ action('MeasurementsController@kyotoLongitude') }}">Kyoto Longitude</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ action('DownloadController@index') }}">Raw Data</a>
            </li>
            @if (Auth::user())
                <li class="navbar-link pull-right">
                    <a class="nav-link" href="{{ action('Auth\AuthController@getLogout') }}">Logout</a>
                </li>
            @else
                <li class="navbar-link pull-right">
                    <a class="nav-link" href="{{ action('Auth\AuthController@getLogin') }}">Login</a>
                </li>
            @endif

        </ul>
    </nav>

    @yield('master_content')

    <script src="{{ asset('js/flotr2.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
