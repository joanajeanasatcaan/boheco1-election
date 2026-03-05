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
use Illuminate\Support\Facades\Log;

class EcomDataListController extends Controller
{
    private function ecomDistrict(): string|int|null
{
    $user = Auth::user();

    // If no user is detected, the API will crash later or return nothing.
    if (!$user) {
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

        if ($request->filled('voted_method')) {
            $memberQuery->whereHas('voteLogs', function ($query) use ($request) {
                $query->where('voted_method', $request->voted_method);
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $memberQuery->whereHas('verification', function ($query) {
                    $query->where('is_verified', true);
                });
            } elseif ($request->status === 'pending') {
                $memberQuery->where(function ($query) {
                    $query->whereDoesntHave('verification')
                        ->orWhereHas('verification', function ($q) {
                            $q->where('is_verified', false);
                        });
                });
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
