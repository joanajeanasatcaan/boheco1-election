<?php

namespace App\Http\Controllers\Api\Admin;

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
        $perPage  = (int) $request->get('per_page', 100);
        $search   = trim($request->get('search', ''));
        $idSearch = trim($request->get('id_search', ''));

        // ─── ID Search — members only, no spouses ────────────────────────────────
        if ($idSearch !== '') {
            $memberQuery = Member::with(['spouse', 'townDetail', 'barangayDetail'])
                ->where('Id', 'like', "%{$idSearch}%");

            $this->applyFilters($memberQuery, $request);

            return MemberResource::collection(
                $memberQuery->orderBy('Id')->cursorPaginate($perPage)
            );
        }

        // ─── Name Search — members + spouses as separate rows ────────────────────
        if ($search !== '') {
            // Members matching the search
            $memberQuery = Member::with(['spouse', 'townDetail', 'barangayDetail'])
                ->where(function ($q) use ($search) {
                    $q->where('FirstName', 'like', "{$search}%")
                    ->orWhere('LastName',  'like', "{$search}%");
                });

            $this->applyFilters($memberQuery, $request);

            $members = $memberQuery->orderBy('LastName')->orderBy('FirstName')->get();

            // Spouses matching the search — as separate rows
            $spouseQuery = MemberSpouse::with(['member', 'townDetail', 'barangayDetail'])
                ->where(function ($q) use ($search) {
                    $q->where('FirstName', 'like', "{$search}%")
                    ->orWhere('LastName',  'like', "{$search}%");
                });

            $spouses = $spouseQuery->orderBy('LastName')->orderBy('FirstName')->get();

            // ✅ Merge both collections, re-sort by LastName, then paginate manually
            $combined = $members->concat($spouses)
                ->sortBy([
                    ['LastName',  'asc'],
                    ['FirstName', 'asc'],
                ])
                ->values();

            // Manual cursor-style pagination (offset-based fallback for merged results)
            $page    = max(1, (int) $request->get('page', 1));
            $offset  = ($page - 1) * $perPage;
            $slice   = $combined->slice($offset, $perPage)->values();
            $hasMore = $combined->count() > ($offset + $perPage);

            return response()->json([
                'data' => MemberResource::collection($slice),
                'meta' => [
                    'per_page'    => $perPage,
                    'total'       => $combined->count(),
                    'page'        => $page,
                    'has_more'    => $hasMore,
                    'next_cursor' => $hasMore ? (string)($page + 1) : null,
                    'prev_cursor' => $page > 1 ? (string)($page - 1) : null,
                ],
            ]);
        }

        // ─── Default — members only, order by Id ─────────────────────────────────
        $memberQuery = Member::with(['spouse', 'townDetail', 'barangayDetail']);
        $this->applyFilters($memberQuery, $request);

        return MemberResource::collection(
            $memberQuery->orderBy('Id')->cursorPaginate($perPage)
        );
    }

    // ─── Extracted filter helper ──────────────────────────────────────────────────
    private function applyFilters($query, $request): void
    {
        if ($request->filled('district')) {
            $query->whereHas('townDetail', function ($q) use ($request) {
                $q->where('District', $request->district);
            });
        }

        if ($request->filled('gender')) {
            $query->where('Gender', $request->gender);
        }

        if ($request->filled('town')) {
            $town = Town::where('Town', $request->town)->first();
            if ($town) $query->where('Town', $town->id);
        }

        if ($request->filled('barangay')) {
            $query->whereHas('barangayDetail', function ($q) use ($request) {
                $q->where('Barangay', $request->barangay);
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'Verified') {
                $query->whereHas('verification', function ($q) {
                    $q->where('is_verified', true);
                });
            } elseif ($request->status === 'Pending') {
                $query->where(function ($q) {
                    $q->whereDoesntHave('verification')
                    ->orWhereHas('verification', function ($q) {
                        $q->where('is_verified', false);
                    });
                });
            }
        }
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
