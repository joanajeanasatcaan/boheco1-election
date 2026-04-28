<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nominee;
use App\Models\VoteLog;
use App\Models\VoterVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TallyController extends Controller
{
    public function index(Request $request)
    {
        $districtFilter = $request->district;

        // ── 1. Get all nominees grouped by district
        $nomineeQuery = Nominee::withCount([
            'votes as votes_cast' => fn($q) => $q->distinct('member_id'),
        ]);

        if ($districtFilter && $districtFilter !== 'all') {
            $nomineeQuery->where('district', $districtFilter);
        }

        $nominees = $nomineeQuery->get()->groupBy(fn($n) => (int) $n->district);

        // ── 2. Get abstain counts per district from VoteLog
        //    Abstains have nominee_id = NULL; we find the member's district
        //    via Member → townDetail
        $abstainQuery = VoteLog::whereNull('nominee_id')
            ->join('CRM_MemberConsumers as m', 'ECRM_VoteLogs.member_id', '=', 'm.Id')
            ->join('CRM_Towns as t', 'm.Town', '=', 't.Id')
            ->selectRaw('t.District as district, COUNT(DISTINCT ECRM_VoteLogs.member_id) as abstain_count')
            ->groupBy('t.District');

        if ($districtFilter && $districtFilter !== 'all') {
            $abstainQuery->where('t.District', $districtFilter);
        }

        $abstainsByDistrict = $abstainQuery->get()
            ->keyBy('district')
            ->map(fn($row) => (int) $row->abstain_count);

        // ── 3. Build response — include every district that has nominees,
        //    and also any districts that only have abstains
        $allDistricts = $nominees->keys()
            ->merge($abstainsByDistrict->keys())
            ->unique()
            ->sort()
            ->values();

        $response = [];

        foreach ($allDistricts as $districtNumber) {
            $items         = $nominees->get($districtNumber, collect());
            $abstainCount  = $abstainsByDistrict->get($districtNumber, 0);

            // Total votes cast = candidate votes + abstains
            $candidateVotes   = $items->sum('votes_cast');
            $totalVotesCast   = $candidateVotes + $abstainCount;

            $registeredVoters = $this->registeredVoters($districtNumber);

            $response[] = [
                'district'        => "District {$districtNumber}",
                'votesCast'       => $totalVotesCast,
                'candidateVotes'  => $candidateVotes,
                'abstainCount'    => $abstainCount,
                'totalVoters'     => $registeredVoters,
                'turnout'         => $registeredVoters > 0
                    ? round(($totalVotesCast / $registeredVoters) * 100)
                    : 0,
                'status'          => 'Live',
                'candidates'      => $items->map(function ($nominee) use ($candidateVotes) {
                    return [
                        'name'       => trim($nominee->first_name . ' ' . $nominee->last_name),
                        'votes'      => $nominee->votes_cast,
                        // Percentage out of candidate votes only (excludes abstains)
                        'percentage' => $candidateVotes > 0
                            ? round(($nominee->votes_cast / $candidateVotes) * 100)
                            : 0,
                    ];
                })->values(),
            ];
        }

        return response()->json($response);
    }

    private function registeredVoters(int $district): int
    {
        return VoterVerification::where('is_verified', true)
            ->whereHas('member.townDetail', function ($q) use ($district) {
                $q->where('District', $district);
            })
            ->distinct('voter_id')
            ->count('voter_id');
    }
}