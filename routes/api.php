<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\OrderController;
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
    // Registro de dados do animal
    Route::get('animal/{id?}', [AnimalController::class, 'animalGet'])->name('animalGet');
    Route::post('animal', [AnimalController::class, 'animalPost'])->name('animalPost');
    Route::put('animal', [AnimalController::class, 'animalPut'])->name('animalPut');
    Route::delete('animal/{id}', [AnimalController::class, 'animalDelete'])->name('animalDelete');
    // ---------------------------

    // Registro de exames
    Route::get('lab/exame/{id?}', [ExamController::class, 'examGet'])->name('examGet');
    Route::post('lab/exame', [ExamController::class, 'examPost'])->name('examPost');
    Route::put('lab/exame', [ExamController::class, 'examPut'])->name('examPut');
    Route::delete('lab/exame/{id}', [ExamController::class, 'examDelete'])->name('examDelete');
    // ------------------

    // Pedidos
    Route::get('lab/order/request/{id?}', [OrderController::class, 'orderRequestGet'])->name('orderRequestGet');
    Route::post('lab/order/request', [OrderController::class, 'orderRequestPost'])->name('orderRequestPost');
    Route::get('lab/order/{id?}', [OrderController::class, 'labOrderGet'])->name('labOrderGet');
    Route::post('lab/order', [OrderController::class, 'labOrderPost'])->name('labOrderPost');
});

// Login e Registro
Route::middleware(['auth.token'])->get('auth/user', [AuthController::class, 'getUser'])->name('getUser');
Route::post('auth/login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');