<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Resources\VotersNomineeResource;
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
        return VotersNomineeResource::collection($nominees);
    }
}