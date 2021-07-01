<?php

namespace App\Http\Controllers;

use App\Models\Events;

class CalendarController extends Controller
{
    public function index()
    {
        return Events::all();
    }
}
