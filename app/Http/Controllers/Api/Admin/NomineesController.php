<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\NomineeResource;
use App\Models\Nominee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\NomineeRequest;
use App\Http\Requests\UpdateNomineeRequest;
use App\Services\NomineeService;    

class NomineesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nominees = Nominee::withCount('votes')->get();

        return NomineeResource::collection($nominees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NomineeRequest $request, NomineeService $service)
    {
        $nominee = $service->create(
            $request->validated(),
            $request->file('image')
        );

        return new NomineeResource($nominee);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $nominee = Nominee::find($id);

        if (!$nominee) {
            return response()->json(['message' => 'Nominee not found'], 404);
        }

        return new NomineeResource($nominee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNomineeRequest $request, string $id, NomineeService $service)
    {
        $nominee = Nominee::find($id);

        if (!$nominee) {
            return response()->json(['message' => 'Nominee not found'], 404);
        }

        $validated = $request->validated();

        $nominee = $service->update(
            $nominee,
            $validated,
            $request->file('image')
        );

        return new NomineeResource($nominee);
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, NomineeService $service)
    {
        $nominee = Nominee::find($id);

        if (!$nominee) {
            return response()->json(['message' => 'Nominee not found'], 404);
        }

        $service->delete($nominee);

        return response()->json(['message' => 'Nominee deleted successfully']);
    }
}
