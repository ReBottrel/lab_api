<?php

use App\Models\Exam;
use App\Models\Parceiro;
use App\Models\OrdemServico;
use App\Models\OrderRequest;
use function Ramsey\Uuid\v6;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\TesteController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\Admin\FurController;
use App\Http\Controllers\Loja\HomeController;
use App\Http\Controllers\Admin\MarkController;
use App\Http\Controllers\Admin\AjudaController;
use App\Http\Controllers\Admin\CupomController;
use App\Http\Controllers\Admin\DadosController;
use App\Http\Controllers\Admin\ExameController;
use App\Http\Controllers\Admin\LaudoController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OwnerController;
use App\Http\Controllers\Admin\AlelosController;
use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\AnimaisController;
use App\Http\Controllers\Admin\TecnicoController;
use App\Http\Controllers\Admin\AppOrderController;
use App\Http\Controllers\Admin\ParceiroController;
use App\Http\Controllers\User\UserDadosController;
use App\Http\Controllers\User\UserOrderController;
use App\Http\Controllers\User\Auth\LoginController;
use App\Http\Controllers\Veterinario\VetController;
use App\Http\Controllers\Admin\DataColetaController;
use App\Http\Controllers\Admin\RelatoriosController;
use App\Http\Controllers\Admin\AnimalOrderController;
use App\Http\Controllers\Admin\OrdemServicoController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Admin\ApiMangalargaController;
use App\Http\Controllers\Admin\HomeController as Admin;
use App\Http\Controllers\Admin\SpeciesBreedsController;
use App\Http\Controllers\Veterinario\AuthVetController;
use App\Http\Controllers\Veterinario\ResenhaController;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Veterinario\VetOrderController;
use App\Http\Controllers\Veterinario\VetOwnerController;
use App\Http\Controllers\Veterinario\VetAnimalController;
use App\Http\Controllers\Veterinario\VetConfigController;

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



Route::get('cep-get', [AddressController::class, 'getCep'])->name('cep.get');
Route::post('login-post', [LoginController::class, 'login'])->name('login.custom');

Route::get('/', [HomeController::class, 'index'])->name('loja');
Route::get('privacy-and-policies', [HomeController::class, 'privacy'])->name('privacy');
Route::get('validacao/{code}', [LaudoController::class, 'verLaudoQrCode']);
Route::get('teste-zap-api', [TesteController::class, 'testeZapApi']);

Route::get('admin-login', [AdminAuthController::class, 'index'])->name('admin.login');
Route::post('admin-login-entrar', [AdminAuthController::class, 'login'])->name('admin.entrar');

Route::middleware(['auth:web'])->group(function () {
    Route::get('user-orders/{id}', [UserDashboardController::class, 'orders'])->name('user.orders');
    Route::get('user-dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('order-done', [UserDashboardController::class, 'ordersDone'])->name('orders.done');
    Route::get('order-done-datail/{id}', [UserDashboardController::class, 'ordersDoneDetail'])->name('orders.done.detail');

    // Route::get('user-dashboard', [UserDashboardController::class, 'maintrance'])->name('user.dashboard');

    Route::get('user-payment/{id}', [UserDashboardController::class, 'paymentMethod'])->name('user.payment');
    Route::post('user-payment-process', [UserDashboardController::class, 'payments'])->name('user.payment.process');

    Route::post('gateway/payment', [GatewayController::class, 'payment'])->name('user.checkout');

    Route::get('gateway/payment/success/{id?}', [GatewayController::class, 'success'])->name('user.success');

    Route::get('user-dados', [UserDadosController::class, 'index'])->name('user.dados');
    Route::get('user-address', [UserDadosController::class, 'address'])->name('user.address');
    Route::post('user-address-update', [UserDadosController::class, 'updateAddress'])->name('user.update.address');

    Route::get('user-cep-get', [UserDadosController::class, 'getCep'])->name('user.get.cep');

    Route::get('user-edit-dados', [UserDadosController::class, 'editDados'])->name('user.edit.dados');
    Route::post('user-update-dados', [UserDadosController::class, 'updateUser'])->name('user.update.dados');

    Route::post('value-update/{id}', [UserDashboardController::class, 'updateValue']);
    Route::post('discount-apply', [UserDashboardController::class, 'discount'])->name('discount.apply');

    Route::post('update-paynow', [UserDashboardController::class, 'updatePayNow'])->name('update.paynow');
});


Route::middleware(['auth:admin'])->group(function () {

    Route::get('ajuda-erro-2001', [AjudaController::class, 'erroPagamento'])->name('ajuda.erro.2001');

    Route::post('get-codlab-relatorio', [RelatoriosController::class, 'getCodlab'])->name('get.codlab.relatorio');

    Route::get('new-orders', [OrderController::class, 'getNewOrders'])->name('get.new.orders');

    Route::get('gerar-xml', [LaudoController::class, 'gerarXML'])->name('gerar.xml');
    Route::get('envia-xml', [LaudoController::class, 'enviaXML'])->name('envia.xml');
    Route::post('atualiza-laudo-status', [LaudoController::class, 'alteraStatus'])->name('atualiza.laudo.status');
   
    Route::get('update-status-animal-mass', [TesteController::class, 'updateStatus'])->name('update.status.animal.mass');
    Route::get('get-all-order-not-create', [TesteController::class, 'getOrderNotCreate'])->name('get.all.order.not.create');
    Route::get('get-all-codlab', [TesteController::class, 'selectCodlabInRange'])->name('get.all.codlab');
    Route::get('get-duplicated-codlab', [TesteController::class, 'exportDuplicatedCodlabToTxt'])->name('get.duplicated.codlab');
    Route::get('get-duplicated-codlab-export', [TesteController::class, 'updateAndExportDuplicatedCodlabToTxt'])->name('get.duplicated.codlab.export');
    Route::get('get-all-codlab-update', [TesteController::class, 'updateCodlabInRange'])->name('get.all.update.codlab');
    Route::get('ver-pdf/{id}', [TesteController::class, 'pdfLaudo'])->name('ver.pdf');
    Route::get('alelos-duplicados', [TesteController::class, 'alelosDuplicados'])->name('alelos.duplicados');
    Route::get('alelos-duplicados-delete', [TesteController::class, 'apagarAlelosDuplicados'])->name('alelos.duplicados.delete');
    Route::get('get-especies-all', [TesteController::class, 'mudarEspecie'])->name('get.especies.all');
    Route::get('get-payments', [TesteController::class, 'getPagamentos'])->name('get.payments');
    Route::get('get-orders-duplicadas', [TesteController::class, 'getOrdemServicosDuplicadas']);
    Route::get('delete-orders-duplicadas', [TesteController::class, 'deleteOrdemServicosDuplicadasSemDataBar']);
    Route::get('alterar-status-laudo', [TesteController::class, 'alterarStatusLaudo']);
    Route::get('alterar-status-animal-view', [TesteController::class, 'viewStatus']);
    Route::post('alterar-status-animal-store', [TesteController::class, 'alterarStatusAnimalByOrder'])->name('alterar.status.animal.store');
    Route::get('get-laudo-total-exclusao', [TesteController::class, 'getLaudoTotal'])->name('get.laudo.total.exclusao');
    Route::get('get-laudo-total', [TesteController::class, 'getLaudosTotal'])->name('get.laudo.total');

    Route::get('relatorios', [RelatoriosController::class, 'index'])->name('relatorios');  
    Route::get('get-laudo-total-exclusao', [RelatoriosController::class, 'getLaudoTotal'])->name('get.laudo.total.exclusao');
    Route::get('get-laudo-total-exclusao-genitora', [RelatoriosController::class, 'getLaudoTotalGenitora'])->name('get.laudo.total.exclusao.genitora');
    Route::get('get-laudo-total-exclusao-genitor', [RelatoriosController::class, 'getLaudoTotalGenitor'])->name('get.laudo.total.exclusao.genitor');
    Route::get('get-laudo-total', [RelatoriosController::class, 'getLaudosTotal'])->name('get.laudo.total');

    Route::post('search-by-codlab', [OrdemServicoController::class, 'searchByCodlab'])->name('search.by.codlab');
    Route::post('search-by-animal', [OrdemServicoController::class, 'searchByAnimal'])->name('search.by.animal');
    Route::get('result-by-codlab/{id}', [OrdemServicoController::class, 'resultado'])->name('result.by.codlab');

    Route::get('import-txt-view', [AlelosController::class, 'importTxt'])->name('import.txt.view');

    Route::get('laudo', function () { return view('admin.ordem-servico.laudo');})->name('laudo');
    Route::get('laudo-table', function () { return view('admin.ordem-servico.laudo-table');})->name('laudo-table');
    Route::get('laudo-html', function () { return view('admin.ordem-servico.laudo-html');})->name('laudo-html');
    Route::post('pre-confirm', [LaudoController::class, 'preConfirm'])->name('pre.confirm');
    // Route::get('laudo', function () {
    //     return view('admin.ordem-servico.laudo');
    // })->name('laudo');

    // Relatorios de ensaios
    Route::get('relatorio/aie', function () {
        return view('admin.ordem-servico.relatorios-de-ensaios.aie');
    })->name('relatorio.aie');
    Route::get('relatorio/homozigose-tobiana', function () {
        return view('admin.ordem-servico.relatorios-de-ensaios.homozigose');
    })->name('relatorio.homozigose');
    Route::get('relatorio/beta-caseina', function () {
        return view('admin.ordem-servico.relatorios-de-ensaios.beta-caseina');
    })->name('relatorio.beta-caseina');

    Route::get('laudos', [LaudoController::class, 'index'])->name('laudos');
    Route::get('laudo-download/{id}', [LaudoController::class, 'downloadLaudo'])->name('laudo.download');

    Route::post('gerar-laudo', [LaudoController::class, 'store'])->name('gerar.laudo');
    Route::get('ver-laudo/{id}', [LaudoController::class, 'show'])->name('ver.laudo');
    // Route::get('gerar-pdf', [TesteController::class, 'gerarPdf'])->name('gerar.pdf');
    // Route::get('ver-pdf', [TesteController::class, 'verPdf'])->name('ver.pdf');
    Route::get('gerar-pdf/{id}', [LaudoController::class, 'gerarPdf'])->name('gerar.pdf');
    Route::get('validacao/{codigo}', [LaudoController::class, 'verify'])->name('verify.pdf');
    Route::post('finalizar-laudo', [LaudoController::class, 'finalizar'])->name('finalizar.laudo');
    Route::post('search-laudo', [LaudoController::class, 'searchByAnimal'])->name('search.laudo');
    Route::post('search-laudo-codlab', [LaudoController::class, 'searchByCodlab'])->name('search.laudo.codlab');

    Route::get('alelos-create', [AlelosController::class, 'alelosCreate'])->name('alelos.create');
    Route::get('alelos-get-api', [AlelosController::class, 'alelosApi'])->name('alelos.get.api');
    Route::post('alelos-api', [AlelosController::class, 'api'])->name('alelos.api');
    Route::post('alelos-store', [AlelosController::class, 'store'])->name('alelos.store');
    Route::post('animais-alelo-buscar', [AlelosController::class, 'getAnimal'])->name('animais.buscar');
    Route::post('animais-alelo-buscar-codlab', [AlelosController::class, 'getAnimalCodlab'])->name('animais.buscar.codlab');
    Route::post('store-alelos-custom', [AlelosController::class, 'storeAlelo'])->name('alelos.store.custom');
    Route::post('alelos-delete', [AlelosController::class, 'destroyAlelos'])->name('alelos.delete');
    Route::post('alelos-replicate', [AlelosController::class, 'replicate'])->name('alelos.replicate');

    Route::post('data-bar-store', [OrdemServicoController::class, 'dataBarCode'])->name('data.bar.store');
    Route::post('ordem-delete', [OrdemServicoController::class, 'delete'])->name('ordem.delete');
    Route::post('alelo-update', [OrdemServicoController::class, 'aleloUpdate'])->name('alelo.update');
    Route::get('get-result-alelo/{id}', [OrdemServicoController::class, 'getResult'])->name('get.result.alelo');
    Route::post('ordem-search', [OrdemServicoController::class, 'searchByOrder'])->name('ordem.search');

    Route::post('result-store', [OrdemServicoController::class, 'storeResult'])->name('result.store');
    Route::post('result-get', [OrdemServicoController::class, 'getResult'])->name('result.get');

    Route::post('data-analise', [OrdemServicoController::class, 'dataAnalise'])->name('data.analise');

    Route::post('import-txt', [OrdemServicoController::class, 'importFile'])->name('import.txt');
    Route::get('alelo-compare/{id}', [OrdemServicoController::class, 'compareAlelo'])->name('alelo.compare');
    Route::post('alelo-analise', [OrdemServicoController::class, 'analise'])->name('alelo.analise');

    Route::get('painel', [Admin::class, 'index'])->name('admin');
    Route::get('order-detail/{id}', [OrderController::class, 'orderDetail'])->name('order.detail');
    Route::get('order-json/{id}', [HomeController::class, 'orderJson'])->name('order.json');

    Route::post('chip/{id}', [OrderController::class, 'chip'])->name('chip');
    Route::post('amostra/{id}', [OrderController::class, 'amostra'])->name('amostra');
    Route::post('parceiro-update-order', [OrderController::class, 'alterarParceiro'])->name('order.parceiro.update');

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
    Route::get('order-edit/{id}', [OrderController::class, 'editOrder'])->name('order.edit');
    Route::post('order-owner-update/{id}', [OrderController::class, 'editarProprietario'])->name('order.owner.update');
    Route::post('order-tecnico-update/{id}', [OrderController::class, 'editarTecnico'])->name('order.tecnico.update');
    Route::post('order-order-update/{id}', [OrderController::class, 'updateOrderData'])->name('order.order.update');


    Route::get('owner/{id}', [OrderController::class, 'owner'])->name('orders.owner');

    Route::get('orders-vet', [OrderController::class, 'orderVet'])->name('orders.vet');
    Route::get('orders-vet-detail/{id}', [OrderController::class, 'orderVetDetail'])->name('orders.vet.detail');
    Route::post('app-order-status/{id}', [AppOrderController::class, 'status'])->name('app.order.status');

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
    Route::post('filter-search-number', [OrderController::class, 'searchNumber'])->name('filter.search.number');
    Route::post('filter-search-animal', [OrderController::class, 'searchAnimal'])->name('filter.search.animal');
    Route::post('filter-search-codlab', [OrderController::class, 'searchCodlab'])->name('filter.search.codlab');
    Route::post('filter-payment', [OrderController::class, 'filterPayment'])->name('filter.payment');
    Route::get('filter-date', [OrderController::class, 'dateFilter'])->name('filter.date');

    Route::post('filter-vet-status', [AppOrderController::class, 'filter'])->name('filter.vet.status');

    Route::get('owners', [OwnerController::class, 'index'])->name('owners');
    Route::get('owner-edit/{id}', [OwnerController::class, 'edit'])->name('owner.edit');
    Route::post('owner-update/{id}', [OwnerController::class, 'update'])->name('owner.update');
    Route::get('owner-create', [OwnerController::class, 'create'])->name('owner.create');
    Route::get('owners-delete', [OwnerController::class, 'destroyAll'])->name('owners.delete.all');
    Route::get('get-animals/{old_id?}', [OwnerController::class, 'getAnimals'])->name('get.animals');
    Route::any('owner-delete/', [OwnerController::class, 'destroy'])->name('owner.delete');

    Route::get('technical/{id}', [OrderController::class, 'technical'])->name('technical');
    Route::post('technical-add', [OrderController::class, 'technicalStore'])->name('technical.add');

    Route::post('techinical-store', [TecnicoController::class, 'store'])->name('techinical.store');

    Route::get('techinicals', [TecnicoController::class, 'index'])->name('techinicals');
    Route::get('techinical-create', [TecnicoController::class, 'create'])->name('techinical.create');
    Route::get('techinical-edit/{id}', [TecnicoController::class, 'edit'])->name('techinical.edit');
    Route::post('techinical-update/{id}', [TecnicoController::class, 'update'])->name('techinical.update');
    Route::post('techinical-search', [TecnicoController::class, 'search'])->name('techinical.search');
    Route::any('techinical-delete/{id}', [TecnicoController::class, 'destroy'])->name('techinical.delete');

    Route::post('owner-access', [OwnerController::class, 'ownerAcess'])->name('owner.access');

    Route::get('get-ownser-user/{id}', [OwnerController::class, 'getUser'])->name('owner.user');
    Route::post('owner-user-update/{id}', [OwnerController::class, 'updateUser'])->name('owner.user.update');
    Route::get('owner-show/{id}', [OwnerController::class, 'getOwner'])->name('get.owners.details');

    Route::post('owners-search', [OwnerController::class, 'search'])->name('owners.search');

    Route::get('config', [ConfigController::class, 'index'])->name('configs');

    Route::get('order-admin-create', [OrderController::class, 'orderCreate'])->name('order.create.painel');
    Route::post('order-admin-store', [OrderController::class, 'requestPost'])->name('order.store.painel');
    Route::get('add-animal/{id}/{type}', [OrderController::class, 'orderAnimal'])->name('admin.order-animal');


    Route::get('add-animal-create/{id}', [OrderController::class, 'addAnimalToOrder'])->name('admin.order-create-animal');
    Route::get('add-parentesco-create/{id}', [OrderController::class, 'addAnimalParentescoCreate'])->name('admin.order-create-parentesco');
    Route::post('add-animal-update', [OrderController::class, 'updateAnimalOrder'])->name('admin.order-update-animal');
    Route::post('add-animal-post', [OrderController::class, 'orderAddAnimalPost'])->name('admin.order-add-animal-post');
    Route::any('product-delete/{id}', [OrderController::class, 'orderAddAnimalDelete'])->name('admin.produto.delete');
    Route::get('order-admin-end/{id}', [OrderController::class, 'orderEnd'])->name('order.end.painel');
    Route::post('order-edit-animal/{id}', [OrderController::class, 'orderAddAnimalEdit'])->name('order.edit.animal');
    Route::post('busca-animal-codlab', [OrderController::class, 'buscaCodlab'])->name('busca.codlab.animal');


    Route::get('admin-edit/{id}', [ConfigController::class, 'adminEdit'])->name('config.edit.admin');
    Route::post('admin-update/{id}', [AdminAuthController::class, 'update'])->name('config.update.admin');
    Route::any('admin-logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::any('admin-delete/{id}', [AdminAuthController::class, 'destroy'])->name('admin.delete');


    Route::any('order-delete/{id}', [OrderController::class, 'orderDelete'])->name('orders.delete');


    Route::any('update-mass', [OrderController::class, 'massUpdate']);


    Route::get('animais', [AnimaisController::class, 'index'])->name('animais');
    Route::get('animal-create', [AnimaisController::class, 'create'])->name('animais.create');
    Route::post('animal-store', [AnimaisController::class, 'store'])->name('animais.store');
    Route::get('search-animal', [AnimaisController::class, 'search'])->name('search.animal');
    Route::get('animal-show/{id}', [AnimaisController::class, 'show'])->name('animais.show');
    Route::get('animal-edit/{id}', [AnimaisController::class, 'edit'])->name('animais.edit');
    Route::post('animal-update/{id}', [AnimaisController::class, 'update'])->name('animais.update');
    Route::get('animal-status/{id}', [AnimaisController::class, 'showStatus'])->name('animais.status');
    Route::get('animal-get-status/{id}', [AnimaisController::class, 'getStatus'])->name('animais.get.status');
    Route::post('animal-status-update/{id}', [AnimaisController::class, 'statusUpdate'])->name('animais.status.update');
    Route::get('animais-complete', [AnimaisController::class, 'getAnimal'])->name('animais.get.complete');
    Route::post('animal-delete', [AnimaisController::class, 'destroy'])->name('animais.delete');

    Route::get('cupons', [CupomController::class, 'index'])->name('cupons');
    Route::post('cupons-store', [CupomController::class, 'store'])->name('cupons.store');
    Route::any('cupom-delete/{id}', [CupomController::class, 'destroy'])->name('cupom.delete');

    Route::get('species', [SpeciesBreedsController::class, 'index'])->name('species');
    Route::post('species-store', [SpeciesBreedsController::class, 'store'])->name('species.store');

    Route::get('breeds', [SpeciesBreedsController::class, 'breeds'])->name('breeds');
    Route::post('breeds-store', [SpeciesBreedsController::class, 'storeBreed'])->name('breeds.store');

    Route::get('get-breeds/{id}', [SpeciesBreedsController::class, 'getBreed'])->name('get.breed');
    Route::post('get-pai', [AnimaisController::class, 'getPai'])->name('get.pai');
    Route::post('get-mae', [AnimaisController::class, 'getMae'])->name('get.mae');

    Route::get('get-animals-all', [AnimaisController::class, 'buscarAnimal'])->name('get.animals.all');
    Route::post('search-codlab-animal', [AnimaisController::class, 'searchCodLab'])->name('search.codlab.animal');

    Route::get('export-order', [OrderController::class, 'exportOrders']);
    Route::get('export-pendentes', [OrderController::class, 'exportPedentes']);

    Route::get('/get-registros', [AnimaisController::class, 'getRegistros'])->name('get.registros.animais');

    Route::post('store-animal-parentesco', [AnimalOrderController::class, 'storeAnimalParentesco'])->name('store.animal.parentesco');
    Route::post('store-animal-homozigose', [AnimalOrderController::class, 'storeAnimalHomozigose'])->name('store.animal.homozigose');
    Route::get('show-animal-homozigose/{id}', [AnimalOrderController::class, 'showHomozigose'])->name('show.animal.homozigose');
    Route::get('show-animal-dna/{id}', [AnimalOrderController::class, 'showDna'])->name('show.animal.dna');
    Route::get('edit-animal-homozigose/{id}', [AnimalOrderController::class, 'editHomozigose'])->name('edit.animal.homozigose');

    Route::get('fur', [FurController::class, 'index'])->name('fur');
    Route::post('fur-store', [FurController::class, 'store'])->name('fur.store');



    Route::post('/data-store-resultado', [DataColetaController::class, 'updateData'])->name('datas.resultado.store');
    Route::post('/sample-update', [DataColetaController::class, 'updateTipo'])->name('datas.sample.store');

    Route::post('/data-store-sorologia', [AppOrderController::class, 'updateData'])->name('datas.store');

    Route::get('teste-draw', [TesteController::class, 'index']);
    Route::post('teste-draw-store', [TesteController::class, 'store'])->name('teste.draw');
    Route::get('teste-draw-show', [TesteController::class, 'show'])->name('teste.draw.show');

    Route::get('marks', [MarkController::class, 'index'])->name('marks');
    Route::post('marks-store', [MarkController::class, 'store'])->name('marks.store');


    Route::get('parceiros', [ParceiroController::class, 'index'])->name('parceiros');
    Route::post('parceiros-store', [ParceiroController::class, 'store'])->name('parceiros.store');
    Route::post('parceiros-delete', [ParceiroController::class, 'destroy'])->name('parceiros.delete');

    Route::get('get-dados-owner', [DadosController::class, 'getOwner'])->name('get.dados.owner');
    Route::get('get-dados-tecnico', [DadosController::class, 'getTecnico'])->name('get.dados.tecnico');
    // Route::get('get-dados-tecnico', [DadosController::class, 'getTecnico'])->name('get.dados.tecnico');

    Route::get('get-dados-animal', [DadosController::class, 'getAnimal'])->name('get.dados.animal');

    Route::post('ordem-servico-store', [OrdemServicoController::class, 'store'])->name('ordem.servico.store');
    Route::get('order-servico-all', [OrdemServicoController::class, 'index'])->name('ordem.servico.all');
    Route::get('order-servico-show/{id}', [OrdemServicoController::class, 'show'])->name('ordem.servico.show');
    Route::get('gerar-barcode/{id}', [OrdemServicoController::class, 'gerarBarCode'])->name('gerar.barcode');
    Route::get('ordem-edit/{id}', [OrdemServicoController::class, 'edit'])->name('ordem.servico.edit');
    Route::post('ordem-update/', [OrdemServicoController::class, 'update'])->name('ordem.servico.update');

    Route::get('buscar-pedido-parceiro', [ParceiroController::class, 'searchOrdersView'])->name('buscar.pedido.parceiro');
    Route::post('buscar-pedido-parceiro-store', [ParceiroController::class, 'searchOrders'])->name('buscar.pedido.parceiro.store');

    Route::get('adm-exames-especies', [ExameController::class, 'getByEspecies'])->name('admin.exames.especies');

});

Route::get('vet-login', [AuthVetController::class, 'showLoginForm'])->name('vet.login');
Route::post('vet-login-post', [AuthVetController::class, 'login'])->name('vet.login.submit');
Route::get('vet-register', [AuthVetController::class, 'showRegisterForm'])->name('vet.register');
Route::post('vet-register-post', [AuthVetController::class, 'registerStore'])->name('vet.register.submit');


Route::middleware(['auth:veterinario'])->prefix('vet')->group(function () {

    Route::get('index', [VetController::class, 'index'])->name('vet.index');
    Route::get('select', [VetController::class, 'select'])->name('vet.select');
    Route::get('owner', [VetController::class, 'owners'])->name('vet.owner');
    Route::get('owner-2', [VetController::class, 'owners2'])->name('vet.owner2');


    Route::get('owner-index', [VetOwnerController::class, 'index'])->name('vet.owner.index');
    Route::get('owner-create', [VetOwnerController::class, 'create'])->name('vet.owner.create');
    Route::post('owner-store', [VetOwnerController::class, 'store'])->name('vet.owner.store');

    Route::post('order-store', [VetOrderController::class, 'store'])->name('vet.order.store');

    Route::get('animal-index', [VetAnimalController::class, 'index'])->name('vet.animal.index');
    Route::get('vet-animal-create', [VetAnimalController::class, 'create'])->name('vet.animal.create');
    Route::post('vet-animal-store', [VetAnimalController::class, 'store'])->name('vet.animal.store');
    Route::get('vet-animal-edit/{id}', [VetAnimalController::class, 'edit'])->name('vet.animal.edit');
    Route::post('vet-animal-update/{id}', [VetAnimalController::class, 'update'])->name('vet.animal.update');

    Route::get('animal-select/{id}', [ResenhaController::class, 'animalSelect'])->name('vet.animal.select');
    Route::post('animal-select-store', [ResenhaController::class, 'animalUpdate'])->name('vet.animal.select.store');

    Route::get('animal-create/{id}', [ResenhaController::class, 'animalCreate'])->name('animal.create');
    Route::post('animal-store', [ResenhaController::class, 'animalStore'])->name('animal.store');

    Route::get('resenha-step-1/{id}', [ResenhaController::class, 'step1'])->name('resenha.step1');
    Route::get('resenha-step-2/{id}', [ResenhaController::class, 'step2'])->name('resenha.step2');
    Route::get('resenha-step-3/{id}', [ResenhaController::class, 'step3'])->name('resenha.step3');
    Route::get('resenha-step-4/{id}', [ResenhaController::class, 'step4'])->name('resenha.step4');
    Route::get('resenha-step-5/{id}', [ResenhaController::class, 'step5'])->name('resenha.step5');
    Route::get('resenha-step-6/{id}', [ResenhaController::class, 'step6'])->name('resenha.step6');
    Route::get('resenha-step-7/{id}', [ResenhaController::class, 'step7'])->name('resenha.step7');

    Route::get('order-create/{id}', [VetOrderController::class, 'createOrder'])->name('vet.order.create');

    Route::post('resenha-store-step-1', [ResenhaController::class, 'store'])->name('resenha.store.step1');

    Route::post('order-store-finish', [VetOrderController::class, 'storeOrder'])->name('vet.order.finish');
    Route::get('finish/{id}', [ResenhaController::class, 'finishResenha'])->name('finish');

    Route::get('order-owner-select', [VetOrderController::class, 'ownerSelect'])->name('vet.order.owner.select');

    Route::get('animal-update-view/{id}', [ResenhaController::class, 'animalUpdateView'])->name('animal.update.view');
    Route::post('animal-update-data/{id}', [ResenhaController::class, 'UpdateData'])->name('animal.update.data');

    Route::post('order-list-itens', [VetOrderController::class, 'listItens'])->name('vet.order.list.itens');
    Route::post('store-new-order', [VetOrderController::class, 'createNewOrder'])->name('vet.order.store.new');


    Route::get('configs', [VetConfigController::class, 'index'])->name('vet.configs');
    Route::post('configs-store', [VetConfigController::class, 'updateUser'])->name('vet.configs.store');

    Route::any('logout', [AuthVetController::class, 'sair'])->name('vet.logout');

 

  
});

Route::get('duplicados-e-filhos-daputa', [TesteController::class, 'duplicate']);

Route::get('mangalarga-api', [ApiMangalargaController::class, 'getApi'])->name('api.manga');
Route::get('mangalarga-api-animal', [ApiMangalargaController::class, 'getAnimal'])->name('api.animal');
Route::get('get-resenha', [ApiMangalargaController::class, 'getResenha'])->name('api.resenha');
Route::get('get-resenha-row/{row}', [ApiMangalargaController::class, 'getResenhaRequest'])->name('api.resenha.request');
Route::get('get-coleta-row/{row}', [ApiMangalargaController::class, 'getResenhaRequest'])->name('api.coleta.request');
Route::get('get-row-id', [ApiMangalargaController::class, 'getRowId'])->name('get.row.id');

Route::get('get-states', [AddressController::class, 'estados'])->name('get.states');
Route::get('get-cities', [AddressController::class, 'cidades'])->name('get.cities');

Route::get('view-resenha/{id}', [ResenhaController::class, 'viewResenha'])->name('view.resenha');
Route::get('resenha-pdf/{id}', [ResenhaController::class, 'gerarPDF'])->name('resenha.pdf');


Route::get('teste-email', [TesteController::class, 'testeEnvioEmail']);