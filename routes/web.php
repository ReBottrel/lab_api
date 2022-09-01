<?php

use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\ExameController;
use App\Http\Controllers\Admin\HomeController;
use Illuminate\Support\Facades\Route;

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




Route::middleware(['auth:admin'])->group(function () {
    Route::get('painel', [HomeController::class, 'index'])->name('admin');
    Route::get('exames', [ExameController::class, 'index'])->name('exames');
    Route::post('exames-store', [ExameController::class, 'store'])->name('exame.store');
    Route::any('exames-delete/{id}', [ExameController::class, 'destroy'])->name('exame.delete');

    Route::get('exame-show/{id}', [ExameController::class, 'show'])->name('exame.show');
    Route::post('exames-update', [ExameController::class, 'update'])->name('exame.update');
});
