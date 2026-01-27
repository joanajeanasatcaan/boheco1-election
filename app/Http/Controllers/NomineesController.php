<?php

namespace App\Http\Controllers;
use App\Models\Nominees;

use Illuminate\Http\Request;

class NomineesController extends Controller
{
    public function index(){
        $nominees = Nominees::all();
        return view('admin.nominees', ['nominees' => $nominees]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'town' => 'required|string|max:255',
            'current_votes' => 'required|integer|min:0',
        ]);

        Nominees::create($validated);

        return redirect()->route('nominees')->with('success', 'Nominee added successfully!');
    }
}
