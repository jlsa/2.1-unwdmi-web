<?php

namespace Leertaak5\Http\Controllers;

use Illuminate\Http\Request;

use Leertaak5\Http\Controllers\Controller;

class PagesController extends Controller
{

    /**
     * Show all the measurements in pages
     */

    public function index()
    {
        return view('welcome');
    }

}
