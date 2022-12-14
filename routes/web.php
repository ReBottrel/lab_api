<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TesteController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ExameController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OwnerController;
use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\AnimaisController;
use App\Http\Controllers\Admin\TecnicoController;
use App\Http\Controllers\User\UserOrderController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\User\UserDadosController;
use App\Models\OrderRequest;

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

Route::middleware(['auth:web'])->group(function () {
    Route::get('user-orders', [UserOrderController::class, 'index'])->name('user.orders');
    Route::get('user-dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    // Route::get('user-dashboard', [UserDashboardController::class, 'maintrance'])->name('user.dashboard');

    Route::post('user-payment', [UserDashboardController::class, 'paymentMethod'])->name('user.payment');

    Route::post('gateway/payment', [GatewayController::class, 'payment'])->name('user.checkout');

    Route::get('gateway/payment/success/{id?}', [GatewayController::class, 'success'])->name('user.success');

    Route::get('user-dados', [UserDadosController::class, 'index'])->name('user.dados');
    Route::get('user-address', [UserDadosController::class, 'address'])->name('user.address');
    Route::post('user-address-update', [UserDadosController::class, 'updateAddress'])->name('user.update.address');

    Route::get('user-cep-get', [UserDadosController::class, 'getCep'])->name('user.get.cep');

    Route::get('user-edit-dados', [UserDadosController::class, 'editDados'])->name('user.edit.dados');
    Route::post('user-update-dados', [UserDadosController::class, 'updateUser'])->name('user.update.dados');
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

    Route::get('orders-all', [OrderController::class, 'order'])->name('orders.all');
    Route::get('orders-email', [OrderController::class, 'orderEmail'])->name('orders.email');
    Route::get('orders-sistema', [OrderController::class, 'orderSistema'])->name('orders.sistema');
    Route::get('order-sistema-detail/{id}', [OrderController::class, 'orderSistemaDetail'])->name('order.sistema.detail');

    Route::get('owner/{id}', [OrderController::class, 'owner'])->name('orders.owner');

    Route::get('cep-get', [AddressController::class, 'getCep'])->name('cep.get');

    Route::post('owner-store', [OwnerController::class, 'store'])->name('owner.store');

    Route::post('animal', [OrderController::class, 'animal'])->name('animal');

    Route::get('order-request-detail/{id}', [OrderController::class, 'orderRequestDetail'])->name('order.request.detail');

    Route::post('order-generate', [OrderController::class, 'orderRequestPost'])->name('order.generate');

    Route::post('export', [OrderController::class, 'exportExcel'])->name('export');
    Route::post('export-pay', [OrderController::class, 'exportPay'])->name('export.pay');
    Route::post('export-filter', [OrderController::class, 'exportFilter'])->name('export.filter');

    Route::post('cpf-technical/{id}', [OrderController::class, 'cpfTechnical'])->name('cpf.technical');

    Route::post('filter-status', [OrderController::class, 'filter'])->name('filter.status');
    Route::post('filter-search', [OrderController::class, 'search'])->name('filter.search');
    Route::post('filter-payment', [OrderController::class, 'filterPayment'])->name('filter.payment');
    Route::get('filter-date', [OrderController::class, 'dateFilter'])->name('filter.date');


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
    Route::get('techinical-create', [TecnicoController::class, 'create'])->name('techinical.create');
    Route::get('techinical-edit/{id}', [TecnicoController::class, 'edit'])->name('techinical.edit');
    Route::post('techinical-update/{id}', [TecnicoController::class, 'update'])->name('techinical.update');
    Route::post('techinical-search', [TecnicoController::class, 'search'])->name('techinical.search');

    Route::post('owner-access', [OwnerController::class, 'ownerAcess'])->name('owner.access');

    Route::get('get-ownser-user/{id}', [OwnerController::class, 'getUser'])->name('owner.user');
    Route::post('owner-user-update/{id}', [OwnerController::class, 'updateUser'])->name('owner.user.update');
    Route::get('owner-show/{id}', [OwnerController::class, 'getOwner'])->name('get.owners.details');

    Route::post('owners-search', [OwnerController::class, 'search'])->name('owners.search');

    Route::get('config', [ConfigController::class, 'index'])->name('configs');

    Route::get('order-admin-create', [OrderController::class, 'orderCreate'])->name('order.create.painel');
    Route::post('order-admin-store', [OrderController::class, 'requestPost'])->name('order.store.painel');
    Route::get('add-animal-get/{id}', [OrderController::class, 'orderAddAnimal'])->name('admin.order-add-animal');
    Route::post('add-animal-post', [OrderController::class, 'orderAddAnimalPost'])->name('admin.order-add-animal-post');
    Route::any('product-delete/{id}', [OrderController::class, 'orderAddAnimalDelete'])->name('admin.produto.delete');
    Route::get('order-admin-end/{id}', [OrderController::class, 'orderEnd'])->name('order.end.painel');

    Route::get('admin-edit/{id}', [ConfigController::class, 'adminEdit'])->name('config.edit.admin');
    Route::post('admin-update/{id}', [AdminAuthController::class, 'update'])->name('config.update.admin');
    Route::any('admin-logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::any('admin-delete/{id}', [AdminAuthController::class, 'destroy'])->name('admin.delete');


    Route::any('order-delete/{id}', [OrderController::class, 'orderDelete'])->name('orders.delete');

    Route::any('update-mass', [OrderController::class, 'massUpdate']);


    Route::get('animais', [AnimaisController::class, 'index'])->name('animais');
    Route::get('search-animal', [AnimaisController::class, 'search'])->name('search.animal');
    Route::get('animal-show/{id}', [AnimaisController::class, 'show'])->name('animais.show');
    Route::get('animal-edit/{id}', [AnimaisController::class, 'edit'])->name('animais.edit');
    Route::post('animal-update/{id}', [AnimaisController::class, 'update'])->name('animais.update');
});
