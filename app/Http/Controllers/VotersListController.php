<?php

namespace App\Http\Controllers;
use App\Models\VotersList;

use Illuminate\Http\Request;

class VotersListController extends Controller
{
    public function index(){
        return view('ecom.data');
    }
}
