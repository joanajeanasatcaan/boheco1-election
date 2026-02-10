<?php

namespace App\Http\Controllers\Api;

use App\Models\Member;
use App\Models\MemberSpouse;
use App\Models\Town;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MemberResource;

class MembersController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $search  = trim($request->get('search', ''));

        $memberQuery = Member::with([
            'spouse',
            'townDetail',
            'barangayDetail',
        ]);
        
        if ($request->filled('gender')) {
            $memberQuery->where('Gender', $request->gender);
        }

        if ($request->filled('id')) {
            $memberQuery->where('Id', $request->id);
        }

        if ($request->filled('town')) {
            $town = Town::where('Town', $request->town)->first();
            if ($town) {
                $memberQuery->where('Town', $town->id);
            }
        }

        if ($request->filled('barangay')) {
            $memberQuery->whereHas('barangayDetail', function ($q) use ($request) {
                $q->where('Barangay', $request->barangay);
            });
        }

        if ($search !== '') {

            $memberQuery->where(function ($q) use ($search) {
                $q->where('FirstName', 'like', "%{$search}%")
                  ->orWhere('MiddleName', 'like', "%{$search}%")
                  ->orWhere('LastName', 'like', "%{$search}%");
            });

            $spouseMemberIds = MemberSpouse::where(function ($q) use ($search) {
                    $q->where('FirstName', 'like', "%{$search}%")
                      ->orWhere('MiddleName', 'like', "%{$search}%")
                      ->orWhere('LastName', 'like', "%{$search}%");
                })
                ->pluck('MemberConsumerId')
                ->unique()
                ->toArray();

            if (!empty($spouseMemberIds)) {
                $memberQuery->orWhereIn('Id', $spouseMemberIds);
            }
        }

        return MemberResource::collection(
            $memberQuery
                ->orderBy('Id')
                ->cursorPaginate($perPage)
        );
    }

    public function store(MemberRequest $request)
    {
        $member = Member::create($request->validated());

        return new MemberResource(
            $member->load('spouse')
        );
    }

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



    public function update(UpdateMemberRequest $request, $id)
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        $member->update($request->validated());

        return new MemberResource(
            $member->load('spouse')
        );
    }

    public function destroy($id)
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        $member->delete();

        return response()->json([
            'message' => 'Member deleted successfully'
        ]);
    }
}
