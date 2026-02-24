<?php

namespace App\Http\Controllers;
use App\Models\Districts;

use Illuminate\Http\Request;

class DistrictsController extends Controller
{
    public function index(){
        return view('admin.districts');
    }
}
