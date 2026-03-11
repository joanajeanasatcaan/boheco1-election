<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
     public function show($id)
    {
        $member = Member::with(['spouse', 'townDetail', 'barangayDetail'])->find($id);

        if ($member) {
            return new MemberResource($member);
        }

        $spouse = MemberSpouse::with(['member', 'townDetail', 'barangayDetail'])->find($id);

        if ($spouse) {
            return new MemberResource($spouse);
        }

        // If neither found, return 404
        return response()->json([
            'message' => 'Person not found'
        ], 404);
    }

}
