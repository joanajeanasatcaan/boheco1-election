<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Ecom\VoterVerificationController;
use App\Http\Controllers\Api\Ecom\VoteController;
use App\Http\Controllers\Api\Ecom\EcomDataListController;
use App\Http\Controllers\Api\Admin\MembersController;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\NomineesController;
use App\Http\Controllers\Api\Admin\EcomProfileController;
use App\Http\Controllers\Api\Admin\DashboardDistrictCountController;
use App\Http\Controllers\Api\Admin\DistrictController;
use App\Http\Controllers\Api\Admin\TallyController;
use App\Http\Controllers\Api\Ecom\MemberHistoryController;
use App\Http\Controllers\Api\Admin\ElectionScheduleController;
use App\Http\Controllers\Api\Member\UserInfoController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('members', MembersController::class);
    Route::apiResource('nominees', NomineesController::class);
    Route::apiResource('ecom-profile', EcomProfileController::class);
    Route::get('/dashboard-district-counts', [DashboardDistrictCountController::class, 'index']);
    Route::get('/districts', [DistrictController::class, 'index']);
    Route::get('/tally-results', [TallyController::class, 'index']);
    Route::apiResource('schedules', ElectionScheduleController::class)->except(['show']);
});

Route::prefix('ecom')->middleware('auth:sanctum')->group(function () {
    Route::post('/vote', [VoteController::class, 'vote']);
    Route::post('/voters/verify', [VoterVerificationController::class, 'verify']);
    Route::apiResource('members', EcomDataListController::class);
    Route::delete('history/bulk', [MemberHistoryController::class, 'destroyBulk']);
    Route::apiResource('history', MemberHistoryController::class);
});

Route::get('/members/{id}', [UserInfoController::class, 'show']);
Route::prefix('voters')->middleware('auth:sanctum')->group(function () {
    Route::post('/vote', [VoteController::class, 'vote']);
});