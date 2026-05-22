<?php

use App\Http\Controllers\TodoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. Halaman awal otomatis dialihkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Bungkus fungsi To-Do List di dalam Middleware 'auth' agar wajib login
Route::middleware(['auth', 'verified'])->group(function () {
    // Alamat To-Do List menggunakan URL /dashboard bawaan Breeze
    Route::get('/dashboard', [TodoController::class, 'index'])->name('dashboard');
    
    Route::post('/todo', [TodoController::class, 'store'])->name('todo.store');
    Route::put('/todo/{id}/check', [TodoController::class, 'check'])->name('todo.check');
    Route::put('/todo/{id}', [TodoController::class, 'update'])->name('todo.update');
    Route::delete('/todo/{id}', [TodoController::class, 'destroy'])->name('todo.destroy');
});

// Jalur bawaan Breeze untuk edit profil (biarkan saja)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';