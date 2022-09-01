<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\GeralController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\MarkingController;
use App\Http\Controllers\ContaAzulController;
use App\Http\Controllers\Admin\Auth\AdminAuthController;

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
Route::post('admin-post', [AdminAuthController::class, 'store'])->name('admin.login.store');

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
    Route::get('gateway/payment/{id?}', [GatewayController::class, 'listPayment'])->name('listPayment');
    Route::post('gateway/payment', [GatewayController::class, 'payment'])->name('payment');
    Route::post('gateway/reverse', [GatewayController::class, 'reverse'])->name('reverse');
    #####################################

    ###############CONTROLE DE FIJANCAS NO CONTA AZUL###############
    Route::get('conta-azul/get-url', [ContaAzulController::class, 'getUrlCode'])->name('getUrlCode');
    Route::get('conta-azul/get-categories', [ContaAzulController::class, 'getCategories'])->name('getCategories');
    Route::get('conta-azul/get-sellers', [ContaAzulController::class, 'getSellers'])->name('getSellers');
    Route::post('conta-azul/send-sales', [ContaAzulController::class, 'sendSales'])->name('sendSales');
    ################################################################

    ######################CONTROLES GERAIS######################
    Route::get('tabela-geral', [GeralController::class, 'getTabela'])->name('getTabela');
    Route::post('tabela-geral', [GeralController::class, 'postTabela'])->name('postTabela');
    ############################################################

    ###########################CONTROLE DE INFORMAÇÃO DE USUARIO###########################
    Route::get('auth/users/{user}', [AuthController::class, 'getUsers'])->name('getUsers');
    Route::get('auth/user', [AuthController::class, 'getUser'])->name('getUser');
    // Update user
    Route::put('auth/update-users', [AuthController::class, 'updateUsers'])->name('updateUsers');
    Route::put('auth/update-user', [AuthController::class, 'updateUser'])->name('updateUser');
    #######################################################################################
});

// Login e Registro
Route::post('auth/login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

// Auto Login
Route::post('auth/auto-login', [AuthController::class, 'autoLogin'])->name('autoLogin');

// Callback de gateway
Route::post('gateway/callback-notify', [GatewayController::class, 'callbackNotify'])->name('callbackNotify');

// callback Conta Azul
Route::get('callback/auth-conta-azul', [ContaAzulController::class, 'callbackContaAzul'])->name('callbackContaAzul');
