<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\MemberSpouse;
use App\Models\VoterVerification;
use App\Http\Resources\VoterVerificationResource;

class VoterVerificationController extends Controller
{
    public function verify(Request $request)
    {   
        $request->validate([
            'member_id'    => 'required|string',
        ]);

        $voterId = $request->member_id;

        $member = Member::with('spouse')->find($voterId);

        if (!$member) {
            $spouse = MemberSpouse::with('member')->find($voterId);

            if (!$spouse || !$spouse->member) {
                return response()->json([
                    'message' => 'Voter not found.'
                ], 404);
            }

            $member = $spouse->member;
        }

        $existingVerification = VoterVerification::where('voter_id', $member->Id)->where('is_verified', true)->first();

        if ($existingVerification) {
            return response()->json([
                'message' => 'This household is already verified.',
                'verified_at' => $existingVerification->verified_at,
            ], 409);
        }

        $missing = [];

        if (empty($member->FirstName)) $missing[] = 'first_name';
        if (empty($member->LastName))  $missing[] = 'last_name';
        if (empty($member->Barangay))  $missing[] = 'barangay';
        if (empty($member->Town))      $missing[] = 'town';

        if (empty($member->ContactNumbers) && empty($member->EmailAddress)) {
            $missing[] = 'contact_number_or_email';
        }

        if (!empty($missing)) {
            return response()->json([
                'message' => 'Voter data is incomplete.',
                'missing_fields' => $missing
            ], 422);
        }

        $verification = VoterVerification::updateOrCreate(
            [
                'voter_id' => $member->Id, 
            ],
            [
                'is_verified' => true,
                'verified_at' => now(),
            ]
        );

        return new VoterVerificationResource($verification);
    }
}
