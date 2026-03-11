<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\ElectionSchedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ElectionScheduleResource;

class ElectionScheduleController extends Controller
{
    // GET /api/admin/schedules?year=2026&month=2
   public function index(Request $request)
{
    $year  = $request->year;
    $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);

    $query = ElectionSchedule::query();

    if ($request->filled('year') && $request->filled('month')) {
        $query->where('scheduled_date', 'like', "{$year}-{$month}-%");
    }

    return ElectionScheduleResource::collection(
        $query->orderBy('scheduled_date')->get()
    );
}
    // POST /api/admin/schedules  { scheduled_date, district }
   public function store(Request $request)
{
    $request->validate([
        'scheduled_date' => 'required|date',
        'district'       => 'required|string|max:100',
    ]);

    $date = \Carbon\Carbon::parse($request->scheduled_date)->toDateString();

    if (ElectionSchedule::where('scheduled_date', $date)->exists()) {
        return response()->json(['message' => 'A schedule already exists for this date.'], 422);
    }

    $schedule = ElectionSchedule::create([
        'scheduled_date' => $date,
        'district'       => $request->district,
        'created_by'     => auth()->id() ?? null,
    ]);

    return new ElectionScheduleResource($schedule);
}

    // PUT /api/admin/schedules/{id}  { district }
    public function update(Request $request, $id)
    {
        $schedule = ElectionSchedule::findOrFail($id);

        $validated = $request->validate([
            'district' => 'required|string|max:100',
        ]);

        $schedule->update($validated);

        return new ElectionScheduleResource($schedule);
    }

    // DELETE /api/admin/schedules/{id}
    public function destroy($id)
    {
        $schedule = ElectionSchedule::findOrFail($id);
        $schedule->delete();

        return response()->json(['message' => 'Schedule removed']);
    }
}