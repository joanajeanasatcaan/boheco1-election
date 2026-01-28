<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\MemberSpouse;
use App\Models\VoteLog;
use App\Http\Resources\VoteLogResource;
use App\Models\VoterVerification;

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
            'nominee_id' => 'required|exists:nominees,id',
            'member_id'  => 'required|string',
        ]);

        $voterId = $request->member_id;

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

        $alreadyVoted = VoteLog::where('nominee_id', $request->nominee_id)
            ->where('household_id', $householdId)
            ->exists();

        if ($alreadyVoted) {
            return response()->json([
                'message' => 'This household has already voted.'
            ], 409);
        }

        $vote = VoteLog::create([
            'nominee_id'   => $request->nominee_id,
            'member_id'    => $member->Id,   
            'household_id' => $householdId,
            'ip_address'   => $request->ip(),
        ]);

        return new VoteLogResource($vote);
    }

}
