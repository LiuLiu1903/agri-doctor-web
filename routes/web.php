<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard/{conversation?}', [AIController::class, 'index'])
        ->name('dashboard');

    Route::post('/chat-predict', [AIController::class, 'chatPredict'])
        ->name('chat.predict');

    Route::delete('/chat/{id}', [AIController::class, 'destroy'])->name('chat.destroy');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';