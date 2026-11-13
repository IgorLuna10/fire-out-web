<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\ServicosController;
use App\Http\Controllers\PortaCortaFogoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\AdminMiddleware;

// ── PUBLIC PAGES ──
Route::get('/',                [HomeController::class,          'index'])->name('home');
Route::get('/porta-corta-fogo',[PortaCortaFogoController::class,'index'])->name('porta-corta-fogo');
Route::get('/produtos',        [ProdutosController::class,      'index'])->name('produtos');
Route::get('/produto/{sku}',   [ProdutosController::class,      'show'])->name('produto.show');
Route::get('/servicos',        [ServicosController::class,      'index'])->name('servicos');
Route::get('/contato',         [ContatoController::class,       'index'])->name('contato');
Route::post('/contato',        [ContatoController::class,       'send'])->middleware('throttle:5,1')->name('contato.send');
Route::get('/obrigado',        [ContatoController::class,       'obrigado'])->name('obrigado');

// ── CHECKOUT (Guest Quote Request) ──
Route::post('/checkout/store',  [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout',         [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/submit', [CheckoutController::class, 'submit'])->middleware('throttle:10,1')->name('checkout.submit');
Route::get('/pedido-confirmado',[CheckoutController::class, 'confirmado'])->name('pedido-confirmado');

// ── STAFF / ADMIN AUTH ──
Route::get('/login',            [LoginController::class, 'show'])->name('login');
Route::post('/login',           [LoginController::class, 'login'])->middleware('throttle:30,1')->name('login.post');
Route::post('/logout',          [LoginController::class, 'logout'])->name('logout');

// ── ADMIN ──
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                             [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/pedidos',                      [AdminController::class, 'orders'])->name('orders');
    Route::get('/pedidos/{order}',              [AdminController::class, 'showOrder'])->name('orders.show');
    Route::post('/pedidos/{order}/status',      [AdminController::class, 'updateOrderStatus'])->name('orders.status');
    Route::get('/produtos',                     [AdminController::class, 'products'])->name('products');
    Route::post('/produtos/{product}/estoque',  [AdminController::class, 'updateStock'])->name('products.stock');
    Route::post('/produtos/{product}/preco',    [AdminController::class, 'updatePrice'])->name('products.price');
    Route::post('/produtos/bulk',               [AdminController::class, 'bulkUpdate'])->name('products.bulk');
    Route::post('/produtos/import-csv',         [AdminController::class, 'importCsv'])->name('products.import');
    Route::get('/produtos/export-csv',          [AdminController::class, 'exportCsv'])->name('products.export-csv');
});
