<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\MarkingController;

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

    // Registro de Marcas
    Route::get('lab/marca/{id?}', [MarkingController::class, 'markGet'])->name('markGet');
    Route::post('lab/marca', [MarkingController::class, 'markPost'])->name('markPost');
    Route::put('lab/marca', [MarkingController::class, 'markPut'])->name('markPut');
    Route::delete('lab/marca/{id}', [MarkingController::class, 'markDelete'])->name('markDelete');

    // Registro de exames
    Route::get('lab/exame/{id?}', [ExamController::class, 'examGet'])->name('examGet');
    Route::post('lab/exame', [ExamController::class, 'examPost'])->name('examPost');
    Route::put('lab/exame', [ExamController::class, 'examPut'])->name('examPut');
    Route::delete('lab/exame/{id}', [ExamController::class, 'examDelete'])->name('examDelete');
    // ------------------

    // Pedidos
    Route::get('lab/order/request/{id?}', [OrderController::class, 'orderRequestGet'])->name('orderRequestGet');
    Route::post('lab/order/request', [OrderController::class, 'orderRequestPost'])->name('orderRequestPost');
    Route::put('lab/order/request', [OrderController::class, 'labOrderPut'])->name('labOrderPut');


    #########CONTROLE DE GATEWAY#########
    Route::get('gateway/token', [GatewayController::class, 'generateToken'])->name('generateToken');
    #####################################
});

// Login e Registro
Route::middleware(['auth.token'])->get('auth/user', [AuthController::class, 'getUser'])->name('getUser');
Route::post('auth/login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

// callbacks
Route::get('callback/{callback}', function(\Request $request, $callback = null){
    dd(collect([
        $callback,
        $request->all()
    ]));
});