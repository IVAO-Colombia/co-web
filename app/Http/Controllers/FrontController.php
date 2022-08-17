<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    function comingsoon(){
        return view("website.theme-1.comingsoon");
    }

    function index(){
        return view("website.theme-1.index");
    }
}
