<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Week;
use Illuminate\Http\Request;

class WeekController extends Controller
{
    function listAll(){
        $items = Week::orderBy('id','desc')->paginate(10);
        return response()->json($items);
    }
}
