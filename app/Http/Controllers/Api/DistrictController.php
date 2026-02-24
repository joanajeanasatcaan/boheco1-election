<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nominee;
use Illuminate\Support\Facades\DB;

class DistrictController extends Controller
{   
    public function index()
{
    $totals = DB::table('ECRM_Nominees as nominees')
        ->leftJoin('ECRM_VoteLogs as vote_logs', 'nominees.id', '=', 'vote_logs.nominee_id')
        ->select(
                'nominees.district', 
                DB::raw('COUNT(DISTINCT vote_logs.member_id) as votes_cast'), 
                DB::raw('COUNT(DISTINCT nominees.id) as nominee_count'),
                )
        ->groupBy('nominees.district')
        ->orderBy('nominees.district')
        ->get();

    $totalVotes = DB::table('ECRM_VoteLogs')
        ->distinct()
        ->count('member_id');
    $totalVoters = DB::table('ECRM_VoterVerifications')
        ->where('is_verified', true)
        ->count();

    return response()->json([
        'by_district' => $totals,
        'total_voters' => $totalVoters,
        'total_districts' => $totals->count(),
        'total_votes' => $totalVotes,
    ]);
}
}
