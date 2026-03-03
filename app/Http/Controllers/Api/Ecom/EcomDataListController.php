<?php

namespace App\Http\Controllers\Api\Ecom;

use App\Models\Member;
use App\Models\MemberSpouse;
use App\Models\Town;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EcomListResource;
use App\Http\Requests\MemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use Illuminate\Support\Facades\Auth;

class EcomDataListController extends Controller
{
    private function ecomDistrict(): string|int|null
{
    $user = Auth::user();

    // If no user is detected, the API will crash later or return nothing.
    if (!$user) {
        // Log this to storage/logs/laravel.log so you can see it
        \Log::error('API Access Attempted without Authentication');
        return null; 
    }

    return $user->ecomProfile?->district;
}
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 20);
        $search  = trim($request->get('search', ''));
        $district = $this->ecomDistrict();

        if (!Auth::check()) {
        return response()->json(['debug' => 'Not Logged In'], 401);
    }

        $memberQuery = Member::with([
            'spouse',
            'townDetail',
            'barangayDetail',
        ]);

        if ($district !== null) {
            $memberQuery->whereHas('townDetail', function ($q) use ($district) {
                $q->where('District', $district); 
            });
        }

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

        if ($request->filled('voted')) {
            if ($request->voted === 'voted') {
                $memberQuery->whereNotNull('VotedDate');
            } elseif ($request->voted === 'not_voted') {
                $memberQuery->whereNull('VotedDate');
            }
        }

        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $memberQuery->where('status', true);
            } elseif ($request->status === 'pending') {
                $memberQuery->where('status', false);
            }
        }

        if ($search !== '') {
            $spouseMemberIds = MemberSpouse::where(function ($q) use ($search) {
                    $q->where('FirstName', 'like', "%{$search}%")
                    ->orWhere('MiddleName', 'like', "%{$search}%")
                    ->orWhere('LastName', 'like', "%{$search}%");
                })
                ->pluck('MemberConsumerId')
                ->unique()
                ->toArray();

            $memberQuery->where(function ($q) use ($search, $spouseMemberIds) {
                $q->where('FirstName', 'like', "%{$search}%")
                ->orWhere('MiddleName', 'like', "%{$search}%")
                ->orWhere('LastName', 'like', "%{$search}%");

                if (!empty($spouseMemberIds)) {
                    $q->orWhereIn('Id', $spouseMemberIds);
                }
            });
        }
        
        return EcomListResource::collection(
            $memberQuery
                ->orderBy('Id')
                ->cursorPaginate($perPage)
        );
    }

    public function store(MemberRequest $request)
    {
        $member = Member::create($request->validated());

        return new EcomListResource(
            $member->load('spouse')
        );
    }

    public function show($id)
    {
    $member = Member::with(['spouse', 'townDetail', 'barangayDetail'])->find($id);

    if ($member) {
        return new EcomListResource($member);
    }

    $spouse = MemberSpouse::with(['member', 'townDetail', 'barangayDetail'])->find($id);

    if ($spouse) {
        return new EcomListResource($spouse);
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

        return new EcomListResource(
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
