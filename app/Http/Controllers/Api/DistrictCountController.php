<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nominee;
use App\Http\Resources\DistrictCountResource;

class DistrictCountController extends Controller
{
    public function index()
    {
        $totals = Nominee::query()
            ->from('ECRM_Nominees as nominees') 
            ->join('ECRM_VoteLogs as vote_logs', 'nominees.id', '=', 'vote_logs.nominee_id')
            ->selectRaw('nominees.district, COUNT(DISTINCT vote_logs.member_id) as votes_count')
            ->groupBy('nominees.district')
            ->get();

        return DistrictCountResource::collection($totals);
    }
}