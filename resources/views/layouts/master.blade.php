<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>UNWDMI - @yield('title')</title>

    <link rel="stylesheet" href="{{ asset('style/app.css') }}">
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <a class="navbar-brand" href="#">UNWDMI</a>
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{action('Top10TemperatureController@show')}}">Temperatures</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">Rainfall</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">Kyoto Longitude</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">Raw Data</a>
            </li>
        </ul>
    </nav>
    <div class="container">
        @yield('content')
    </div>


<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
