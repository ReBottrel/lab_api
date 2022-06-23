<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnimalController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth.token'])->group(function(){
    Route::get('animal/{id?}', [AnimalController::class, 'animalGet'])->name('animalGet');
    Route::post('animal', [AnimalController::class, 'animalPost'])->name('animalPost');
    Route::put('animal/{id}', [AnimalController::class, 'animalPut'])->name('animalPut');
    Route::delete('animal/{id}', [AnimalController::class, 'animalDelete'])->name('animalDelete');
});

// Login e Registro
Route::middleware(['auth.token'])->get('auth/user', [AuthController::class, 'getUser'])->name('getUser');
Route::post('auth/login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');