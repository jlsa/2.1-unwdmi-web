<?php

namespace Leertaak5\Http\Controllers;

use Illuminate\Http\Request;
use Leertaak5\Http\Requests;
use Leertaak5\Http\Controllers\Controller;
use Leertaak5\Station;

class BasicController extends Controller
{
    public function showStationInformation()
    {
        $stations = Station::all();
        if(!empty($stations)) {
	        foreach ($stations as $station) {
	            echo $station->name . "<br/>";
	        }
	        echo "Those were the stations!";
	    } else {
	    	echo "No stations!";
	    }
    }
}
