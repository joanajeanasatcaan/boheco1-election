<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nominee;
use App\Http\Resources\DistrictCountResource;
use Illuminate\Support\Facades\DB;


class DashboardDistrictCountController extends Controller
{
    public function index()
    {
        $totals = Nominee::query()
            ->from('ECRM_Nominees as nominees') 
            ->join('ECRM_VoteLogs as vote_logs', 'nominees.id', '=', 'vote_logs.nominee_id')
            ->selectRaw('nominees.district, COUNT(DISTINCT vote_logs.member_id) as votes_count')
            ->groupBy('nominees.district')
            ->get();
        $totalNominees = Nominee::query()
            ->from('ECRM_Nominees as nominees')
            ->selectRaw('COUNT(nominees.id) as total_nominees')
            ->get();

        return response()->json([
            'by_district' => DistrictCountResource::collection($totals),
            'total_votes' => $totalNominees->first()->total_nominees ?? 0,
        ]);
    }

    // public function index()
    // {
    //     $districts = DB::table('districts as d')
    //         ->leftJoin('ECRM_Nominees as n', 'd.district_name', '=', 'n.district')
    //         ->leftJoin('ECRM_VoteLogs as v', 'n.id', '=', 'v.nominee_id')
    //         ->selectRaw('
    //             d.id,
    //             d.district_name,
    //             d.status,
    //             COUNT(DISTINCT n.id) as nominees,
    //             COUNT(DISTINCT v.member_id) as votes_cast
    //         ')
    //         ->groupBy('d.id', 'd.district_name', 'd.status')
    //         ->get();

    //     return response()->json([
    //         'districts' => $districts,
    //         'total_districts' => $districts->count(),
    //         'total_votes' => $districts->sum('votes_cast'),
    //     ]);
    // }
}