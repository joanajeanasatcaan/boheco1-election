<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterListController;
use App\Http\Controllers\DistrictsController;
use App\Http\Controllers\NomineesController;
use App\Http\Controllers\VotersListController;
use App\Http\Controllers\OnlineVotersReceiptsController;
use App\Http\Controllers\Api\MembersController;



Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/districts', [DistrictsController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('districts');

Route::post('/districts', [DistrictsController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('districts.store');

Route::get('/nominees', [NomineesController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('nominees');

Route::post('/nominees', [NomineesController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('nominees.store');

Route::get('/tally-results',function(){
    return view('admin.tally-results');
})->middleware(['auth', 'verified'])->name('tally-results');

Route::get('/schedule', function () {
    return view('admin.schedule');
})->middleware(['auth', 'verified'])->name('schedule');

Route::get('/ecom-accounts', function () {
    return view('admin.ecom-accounts');
})->middleware(['auth', 'verified'])->name('ecom-accounts');

Route::get('/masterlists', [MasterListController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('masterlists');

//Ecom Routes
Route::get('/ecom', [VotersListController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('ecom');

Route::get('/history', function () {
    return view('ecom.history');
})->middleware(['auth', 'verified'])->name('history');

Route::get('/online-voters-receipts', [OnlineVotersReceiptsController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('online-voters-receipts');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
