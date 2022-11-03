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
use App\Http\Controllers\Admin\TecnicoController;
use App\Http\Controllers\TesteController;

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

Route::get('teste-zap-api', [TesteController::class, 'testeZapApi']);

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

    Route::get('owners', [OwnerController::class, 'index'])->name('owners');
    Route::get('owner-edit/{id}', [OwnerController::class, 'edit'])->name('owner.edit');
    Route::post('owner-update/{id}', [OwnerController::class, 'update'])->name('owner.update');
    Route::get('owner-create', [OwnerController::class, 'create'])->name('owner.create');

    Route::get('owners-delete', [OwnerController::class, 'destroyAll'])->name('owners.delete.all');

    Route::get('get-animals/{old_id?}', [OwnerController::class, 'getAnimals'])->name('get.animals');

    Route::get('technical/{id}', [OrderController::class, 'technical'])->name('technical');
    Route::post('technical-add', [OrderController::class, 'technicalStore'])->name('technical.add');

    Route::post('techinical-store', [TecnicoController::class, 'store'])->name('techinical.store');

    Route::get('techinicals', [TecnicoController::class, 'index'])->name('techinicals');
    Route::get('techinical-edit/{id}', [TecnicoController::class, 'edit'])->name('techinical.edit');
    Route::post('techinical-update/{id}', [TecnicoController::class, 'update'])->name('techinical.update');

    Route::post('owner-access', [OwnerController::class, 'ownerAcess'])->name('owner.access');

    Route::get('get-ownser-user/{id}', [OwnerController::class, 'getUser'])->name('owner.user');
    Route::post('owner-user-update/{id}', [OwnerController::class, 'updateUser'])->name('owner.user.update');
    Route::get('owner-show/{id}', [OwnerController::class, 'getOwner'])->name('get.owners.details');

    Route::post('owners-search', [OwnerController::class, 'search'])->name('owners.search');
});
