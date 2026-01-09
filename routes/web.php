<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/districts', function () {
    return view('districts');
})->middleware(['auth', 'verified'])->name('districts');

Route::get('/nominees', function () {
    return view('nominees');
})->middleware(['auth', 'verified'])->name('nominees');

Route::get('/tally-results',function(){
    return view('tally-results');
})->middleware(['auth', 'verified'])->name('tally-results');

Route::get('/schedule', function () {
    return view('schedule');
})->middleware(['auth', 'verified'])->name('schedule');

Route::get('/ecom-accounts', function () {
    return view('ecom-accounts');
})->middleware(['auth', 'verified'])->name('ecom-accounts');

Route::get('/masterlists', function () {
    return view('masterlists');
})->middleware(['auth', 'verified'])->name('masterlists');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
