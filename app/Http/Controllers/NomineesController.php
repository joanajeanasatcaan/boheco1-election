<?php

namespace App\Http\Controllers;
use App\Models\Nominees;

use Illuminate\Http\Request;

class NomineesController extends Controller
{
    public function index(){
        return view('admin.nominees');
    }
}
