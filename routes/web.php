<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ExameController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OwnerController;
use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\User\UserOrderController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Admin\Auth\AdminAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();


Route::get('admin-login', [AdminAuthController::class, 'index'])->name('admin.login');
Route::post('admin-login-entrar', [AdminAuthController::class, 'login'])->name('admin.entrar');

Route::middleware(['auth:web'])->group(function (){
    Route::get('user-orders', [UserOrderController::class, 'index'])->name('user.orders');
    Route::get('user-dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    Route::post('user-payment', [UserDashboardController::class, 'paymentMethod'])->name('user.payment');

    Route::post('gateway/payment', [GatewayController::class, 'payment'])->name('user.checkout');

    Route::get('gateway/payment/success/{id?}', [GatewayController::class, 'success'])->name('user.success');
});


Route::middleware(['auth:admin'])->group(function () {
    Route::get('painel', [HomeController::class, 'index'])->name('admin');
    Route::get('order-detail/{id}', [OrderController::class, 'orderDetail'])->name('order.detail');
    Route::get('order-json/{id}', [HomeController::class, 'orderJson'])->name('order.json');

    Route::post('chip/{id}', [OrderController::class, 'chip'])->name('chip');
    Route::post('amostra/{id}', [OrderController::class, 'amostra'])->name('amostra');

    Route::get('exames', [ExameController::class, 'index'])->name('exames');
    Route::post('exames-store', [ExameController::class, 'store'])->name('exame.store');
    Route::any('exames-delete/{id}', [ExameController::class, 'destroy'])->name('exame.delete');

    Route::get('exame-show/{id}', [ExameController::class, 'show'])->name('exame.show');
    Route::post('exames-update', [ExameController::class, 'update'])->name('exame.update');

    Route::post('recived/{id}', [OrderController::class, 'recivedOrder'])->name('order.recived');

    Route::get('orders', [OrderController::class, 'order'])->name('orders.all');

    Route::get('owner/{id}', [OrderController::class, 'owner'])->name('orders.owner');

    Route::get('cep-get', [AddressController::class, 'getCep'])->name('cep.get');

    Route::post('owner-store', [OwnerController::class, 'store'])->name('owner.store');

    Route::post('animal', [OrderController::class, 'animal'])->name('animal');

    Route::get('order-request-detail/{id}', [OrderController::class, 'orderRequestDetail'])->name('order.request.detail');

    Route::post('order-generate', [OrderController::class, 'orderRequestPost'])->name('order.generate');

    Route::post('export' , [OrderController::class, 'exportExcel'])->name('export');

    Route::post('cpf-technical/{id}', [OrderController::class, 'cpfTechnical'])->name('cpf.technical');

    Route::post('filter-status', [OrderController::class, 'filter'])->name('filter.status');
    Route::post('filter-search', [OrderController::class, 'search'])->name('filter.search');
});
