<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EcomProfile;
use App\Models\User;
use App\Http\Resources\EcomUserResource;
use Illuminate\Support\Facades\Hash;

class EcomProfileController extends Controller
{
    /**
     * List all ecom users (admin only)
     */
    public function index()
    {
        $users = User::whereHas('ecomProfile')
            ->with('ecomProfile')
            ->latest()
            ->paginate(50);

        return EcomUserResource::collection($users);
    }

    /**
     * Create a new ecom user + profile (admin only)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'district' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => false, 
        ]);

        $profile = EcomProfile::create([
            'user_id' => $user->id,
            'district' => $request->district,
            'ecom_password' => $request->password,
        ]);
        
        $user->assignRole('ecom');

        return new EcomUserResource($user);
    }

    /**
     * Update ecom profile of authenticated user
     */
    public function update(Request $request)
    {
        $request->validate([
            'district' => 'required|string|max:255',
        ]);

        $profile = $request->user()->ecomProfile;

        if (! $profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $profile->update([
            'district' => $request->district,
        ]);

        return new EcomUserResource($request->user());
    }

    /**
     * Show ecom profile of authenticated user
     */
    public function show(Request $request)
    {
        $profile = $request->user()->ecomProfile;

        if (! $profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        return new EcomUserResource($request->user());
    }
    
    public function destroy($id)
    {
        $profile = EcomProfile::where('user_id', $id)->first();

        if (!$profile) {
            return response()->json([
                'message' => 'Profile not found'
            ], 404);
        }

        $user = $profile->user;
        $profile->delete();

        if ($user) {
            $user->delete();
        }

        return response()->json([
            'message' => 'Profile and user deleted successfully'
        ]);
    }

}
