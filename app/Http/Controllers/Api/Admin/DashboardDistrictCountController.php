<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\VoteLog;
use App\Http\Resources\DistrictCountResource;
use Illuminate\Support\Facades\DB;

class DashboardDistrictCountController extends Controller
{
    public function index()
    {
        // ── Candidate votes per district (nominee_id NOT NULL)
        $candidateVotes = DB::table('ECRM_VoteLogs as vl')
            ->join('ECRM_Nominees as n', 'vl.nominee_id', '=', 'n.id')
            ->whereNotNull('vl.nominee_id')
            ->selectRaw('n.district, COUNT(DISTINCT vl.member_id) as votes_count')
            ->groupBy('n.district')
            ->get()
            ->keyBy('district');

        // ── Abstain votes per district (nominee_id IS NULL)
        //    Look up district via Member → townDetail
        $abstainVotes = DB::table('ECRM_VoteLogs as vl')
            ->join('CRM_MemberConsumers as m', 'vl.member_id', '=', 'm.Id')
            ->join('CRM_Towns as t', 'm.Town', '=', 't.Id')
            ->whereNull('vl.nominee_id')
            ->selectRaw('t.District as district, COUNT(DISTINCT vl.member_id) as abstain_count')
            ->groupBy('t.District')
            ->get()
            ->keyBy('district');

        // ── Merge: all districts that have any vote
        $allDistricts = $candidateVotes->keys()
            ->merge($abstainVotes->keys())
            ->unique()
            ->sort()
            ->values();

        $totals = $allDistricts->map(function ($district) use ($candidateVotes, $abstainVotes) {
            $candidate = (int) ($candidateVotes->get($district)?->votes_count ?? 0);
            $abstain   = (int) ($abstainVotes->get($district)?->abstain_count ?? 0);

            return (object) [
                'district'       => $district,
                'votes_count'    => $candidate + $abstain,
                'abstain_count'  => $abstain,
                'candidate_votes'=> $candidate,
            ];
        });

        $totalVotes = $totals->sum('votes_count');

        return response()->json([
            'by_district' => DistrictCountResource::collection($totals),
            'total_votes' => $totalVotes,
        ]);
    }
}