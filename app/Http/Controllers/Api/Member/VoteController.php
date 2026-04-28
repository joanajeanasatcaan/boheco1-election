<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\MemberSpouse;
use App\Models\VoteLog;
use App\Http\Resources\VoteLogResource;
use App\Models\VoterVerification;
use App\Models\Nominee;
use App\Events\DistrictVotesUpdated;

class VoteController extends Controller
{
    public function index(Request $request)
    {
        $votes = VoteLog::with(['nominee', 'member'])->get();
        return VoteLogResource::collection($votes);
    }

    public function vote(Request $request)
    {
        $request->validate([
            // nullable — null means abstain
            'nominee_id'   => 'nullable|exists:ECRM_Nominees,id',
            'member_id'    => 'required|string',
            'voted_method' => 'required|string',
        ]);

        $voterId  = $request->member_id;
        $isAbstain = is_null($request->nominee_id);

        $member = Member::with('spouse')->find($voterId);

        if (!$member) {
            $spouse = MemberSpouse::with('member')->find($voterId);
            if ($spouse && $spouse->member) {
                $member = $spouse->member;
            }
        }

        if (!$member) {
            return response()->json([
                'message' => 'The selected member id is invalid.'
            ], 422);
        }

        $householdId = $member->Id;

        $isVerified = VoterVerification::where('voter_id', $householdId)
            ->where('is_verified', true)
            ->exists();

        if (!$isVerified) {
            return response()->json([
                'message' => 'Household is not verified. Please complete verification first.'
            ], 403);
        }

        $alreadyVoted = VoteLog::where('household_id', $householdId)->exists();

        if ($alreadyVoted) {
            return response()->json([
                'message' => 'This household has already voted.'
            ], 409);
        }

        $vote = VoteLog::create([
            'nominee_id'   => $isAbstain ? null : $request->nominee_id,
            'member_id'    => $member->Id,
            'household_id' => $householdId,
            'ip_address'   => $request->ip(),
            'voted_method' => $request->voted_method,
        ]);

        // Only fire district update event for actual (non-abstain) votes
        if (!$isAbstain) {
            $nominee  = Nominee::find($request->nominee_id);
            $district = $nominee->district;

            $votesCount = VoteLog::whereHas('nominee', function ($q) use ($district) {
                    $q->where('district', $district);
                })
                ->whereNotNull('nominee_id')
                ->distinct('household_id')
                ->count('household_id');

            // event(new DistrictVotesUpdated($district, $votesCount));
        }

        return new VoteLogResource($vote);
    }
}