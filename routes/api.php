<?php

use App\Http\Controllers\Api\VoteController;
use App\Http\Controllers\Api\MembersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NomineesController;
use App\Http\Controllers\Api\EcomProfileController;
use App\Http\Controllers\Api\VoterVerificationController;
use App\Http\Controllers\Api\DashboardDistrictCountController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\TallyController;


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('members', MembersController::class);
    Route::apiResource('nominees', NomineesController::class);
    //ecom Routes
    Route::apiResource('ecom-profile', EcomProfileController::class);
    //Voting
    Route::post('/vote', [VoteController::class, 'vote']);
    Route::post('/voters/verify', [VoterVerificationController::class, 'verify']);
    Route::get('/dashboard-district-counts', [DashboardDistrictCountController::class, 'index']);
    Route::get('/districts', [DistrictController::class, 'index']);
    Route::get('/tally-results', [TallyController::class, 'index']);
});
