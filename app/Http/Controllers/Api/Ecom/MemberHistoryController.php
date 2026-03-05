<?php

namespace App\Http\Controllers\Api\Ecom;

use App\Models\MemberHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MemberHistoryResource;

class MemberHistoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 20);

        $query = MemberHistory::with(['member', 'performedBy'])
            ->latest();

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        return MemberHistoryResource::collection(
            $query->paginate($perPage)
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id'   => 'required',
            'type'        => 'required|in:voted,verified,updated',
            'description' => 'nullable|string|max:255',
        ]);

        $history = MemberHistory::create([
            ...$validated,
            'performed_by' => auth()->id(),
        ]);

        return new MemberHistoryResource(
            $history->load(['member', 'performedBy'])
        );
    }

    public function destroy($id)
    {
        $history = MemberHistory::find($id);

        if (!$history) {
            return response()->json(['message' => 'History record not found'], 404);
        }

        $history->delete(); 

        return response()->json(['message' => 'History record deleted']);
    }

    public function destroyBulk(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:member_histories,id',
        ]);

        MemberHistory::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => count($request->ids) . ' record(s) deleted',
        ]);
    }
}