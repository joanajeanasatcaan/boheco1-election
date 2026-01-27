<?php

namespace App\Http\Controllers;
use App\Models\Masterlist;

use Illuminate\Http\Request;

class MasterListController extends Controller
{
    public function index()
{
    $masterlists = Masterlist::all(); 
    
    return view('admin.masterlists', ['masterlists' => $masterlists]);
}
}
