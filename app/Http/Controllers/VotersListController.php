<?php

namespace App\Http\Controllers;
use App\Models\VotersList;

use Illuminate\Http\Request;

class VotersListController extends Controller
{
    public function index(){
        $voters_list = VotersList::all();
        return view('ecom.data', ['voters_list' => $voters_list]);
    }
}
