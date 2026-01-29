<?php

namespace App\Http\Controllers;

class MasterListController extends Controller
{
    public function index()
    {
        return view('admin.masterlists');
    }
}
