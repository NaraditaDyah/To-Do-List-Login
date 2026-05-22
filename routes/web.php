<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [TodoController::class, 'index'])->name('todo.index');
    Route::get('/today', [TodoController::class, 'today'])->name('todo.today');
    Route::post('/todo', [TodoController::class, 'store'])->name('todo.store');
    Route::put('/todo/{id}/check', [TodoController::class, 'check'])->name('todo.check');
    Route::put('/todo/{id}', [TodoController::class, 'update'])->name('todo.update');
    Route::delete('/todo/{id}', [TodoController::class, 'destroy'])->name('todo.destroy');
});

require (__DIR__.'/auth.php');