<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Resources\NomineeResource;
use App\Models\Nominee;
use App\Http\Controllers\Controller;  

class UserNomineeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nominees = Nominee::withCount('votes')->get();
        return NomineeResource::collection($nominees);
    }
}