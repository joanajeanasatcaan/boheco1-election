<?php

namespace App\Http\Controllers;
use App\Models\Districts;

use Illuminate\Http\Request;

class DistrictsController extends Controller
{
    public function index(){
        $districts = Districts::all();

        return view('admin.districts', ['districts' => $districts]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'district_name' => 'required|string|max:255',
            'nominees' => 'required|integer|min:0',
            'registered_voters' => 'required|integer|min:0',
            'votes_cast' => 'required|integer|min:0',
            'status' => 'required|in:Active,Pending,Inactive',
        ]);

        Districts::create($validated);

        return redirect()->route('districts')->with('success', 'District added successfully!');
    }
}
