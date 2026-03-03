<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nominee;
use Illuminate\Http\Request;
use App\Models\VoterVerification;


class TallyController extends Controller
{
    public function index(Request $request)
    {
        $district = $request->district; 

        $query = Nominee::withCount([
            'votes as votes_cast' => function ($q) {
                $q->distinct('member_id');
            }
        ]);

        if ($district && $district !== 'all') {
            $query->where('district', $district);
        }

        $nominees = $query->get()->groupBy(function ($nominee) {
            return (int) $nominee->district;
        });


        $response = [];

        foreach ($nominees as $districtNumber => $items) {

    $totalVotes = $items->sum('votes_cast');
    $registeredVoters = $this->registeredVoters($districtNumber);

    $response[] = [
        'district' => "District {$districtNumber}",
        'votesCast' => $totalVotes,
        'totalVoters' => $registeredVoters,
        'turnout' => $registeredVoters > 0
            ? round(($totalVotes / $registeredVoters) * 100)
            : 0,
        'status' => 'Live',
        'candidates' => $items->map(function ($nominee) use ($totalVotes) {
            return [
                'name' => trim($nominee->first_name . ' ' . $nominee->last_name),
                'votes' => $nominee->votes_cast,
                'percentage' => $totalVotes > 0
                    ? round(($nominee->votes_cast / $totalVotes) * 100)
                    : 0,
            ];
        })->values()
    ];
}


        return response()->json($response);
    }

    private function registeredVoters($district)
    {
        return VoterVerification::where('is_verified', true)
            ->whereHas('member.townDetail', function ($q) use ($district) {
                $q->where('District', (int) $district);
            })
            ->distinct('voter_id')
            ->count('voter_id');
    }
}
