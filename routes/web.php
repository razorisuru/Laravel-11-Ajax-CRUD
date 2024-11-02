<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\studentController;

Route::get('/', function () {
    return view('home');
});


Auth::routes();

Route::get('/home', [studentController::class, 'index'])->name('home');
Route::get('/students', [StudentController::class, 'students'])->name('students.index');
Route::get('/students/{id}', [StudentController::class, 'student']);
Route::post('/store', [studentController::class, 'store'])->name('store');
Route::put('/update/{id}', [studentController::class, 'update'])->name('update');
Route::delete('/destroy/{id}', [studentController::class, 'destroy'])->name('destroy');

