<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/districts', function () {
    return view('admin.districts');
})->middleware(['auth', 'verified'])->name('districts');

Route::get('/nominees', function () {
    return view('admin.nominees');
})->middleware(['auth', 'verified'])->name('nominees');

Route::get('/tally-results',function(){
    return view('admin.tally-results');
})->middleware(['auth', 'verified'])->name('tally-results');

Route::get('/schedule', function () {
    return view('admin.schedule');
})->middleware(['auth', 'verified'])->name('schedule');

Route::get('/ecom-accounts', function () {
    return view('admin.ecom-accounts');
})->middleware(['auth', 'verified'])->name('ecom-accounts');

Route::get('/masterlists', function () {
    return view('admin.masterlists');
})->middleware(['auth', 'verified'])->name('masterlists');

//Ecom Routes
Route::get('/ecom', function () {
    return view('ecom.data');
})->middleware(['auth', 'verified'])->name('ecom');

Route::get('/history', function () {
    return view('ecom.history');
})->middleware(['auth', 'verified'])->name('history');

Route::get('/online-voters-receipts', function () {
    return view('ecom.online-voters-receipts');
})->middleware(['auth', 'verified'])->name('online-voters-receipts');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
