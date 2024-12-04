<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TillController;
use App\Http\Controllers\TransactionController;
use GuzzleHttp\Cookie\SessionCookieJar;
use Illuminate\Contracts\Session\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PageController::class, 'login'])->name('login');
Route::get('/verify', [PageController::class, 'verifyOtp'])->name('verify');
Route::post('/login/user', [AuthController::class, 'login'])->name('login.user');
Route::post('/login/verify', [AuthController::class, 'verifyOtp'])->name('verify.otp');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth.token'])->group(function () {
    // Protected routes
    Route::get('/dashboard', function () {
        return view('index');
    })->name('dashboard');
    Route::get('/getAuth',[AuthController::class,'getAuth']);

    Route::get('/menu', [PageController::class, 'menu'])->name('menu');
    Route::get('/account', [PageController::class, 'account'])->name('account');
    Route::get('/category', [PageController::class, 'category'])->name('category');
    Route::get('/order', [PageController::class, 'order'])->name('order');
    Route::get('/notification', [PageController::class, 'notification'])->name('notification');
    Route::get('/setting', [PageController::class, 'setting'])->name('setting');
    Route::get('/supplier', [PageController::class, 'supplier'])->name('supplier');


    Route::post('/add/category', [CategoryController::class, 'addCategory'])->name('addCategory');
    Route::put('/category/{id}', [CategoryController::class, 'editCategory'])->name('edit.category');
    Route::delete('/category/{id}', [CategoryController::class, 'deleteCategory'])->name('delete.category');

    Route::post('/menu/add', [MenuController::class, 'addMenu'])->name('menu.add');
    Route::get('/getAllCategories', [MenuController::class, 'getCategories'])->name('getCategory');
    Route::put('/menu/{id}', [MenuController::class, 'updateMenuItem'])->name('menu.update');

    // Route for deleting menu item
    Route::delete('/menu/{id}', [MenuController::class, 'deleteMenuItem'])->name('menu.delete');
    Route::get('/get/order', [OrderController::class, 'getOrders'])->name('getOrder');
    Route::get('/get/suppliers', [SupplierController::class, 'getSupplier'])->name('getSuppliers');
    Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('updateOrderStatus');
    Route::post('/addSupplier', [SupplierController::class, 'addSupplier'])->name('add.supplier');
    Route::get('/deleteSupplier/{id}', [SupplierController::class, 'deleteSupplier'])->name('delete.supplier');
    Route::put('/updateSupplier/{id}', [SupplierController::class, 'updateSupplier'])->name('update.supplier');
    Route::post('/add/stock', [InventoryController::class, 'addInventory'])->name('add.stock');
    Route::get('/stock', [PageController::class, 'stock'])->name('stock');
    Route::put('/updatestock/{id}', [InventoryController::class, 'updateStock']);

    Route::get('/deleteStock/{id}', [InventoryController::class, 'deleteStock'])->name('delete.stock');

    Route::get('getAllStocks', [InventoryController::class, 'getStock'])->name('getAllStock');
    Route::put('/updateMenuStock/{id}', [MenuController::class, 'updateMenuStock'])->name('updateMenuStock');
    Route::get('till', [PageController::class, 'till'])->name('till');
    Route::post('/addCustomer', [CustomerController::class, 'addCustomer'])->name('addCustomer');
    Route::get('/getCustomer', [CustomerController::class, 'getCustomer'])->name('getCustomer');
    Route::post('/addData', [SessionController::class, 'addData'])->name('addData');
    Route::get('/get/menu/data', [MenuController::class, 'getMenu'])->name('get.menu');
    Route::get('/clearsession', [SessionController::class, 'clearSession'])->name('clear.session');
    Route::post('/removeItem', [SessionController::class, 'removeItem']);
    Route::get('/getCart', [SessionController::class, 'getCart']);
    Route::get('/getData', [SessionController::class, 'getData']);
    Route::get('/transaction', [TransactionController::class, 'addTransaction']);
    Route::get('/transactions',[TransactionController::class,'getTransactions'])->name('transactions');

    Route::get('/customers',[CustomerController::class,'customerTable'])->name('customers');
    Route::get('/stats',[ReportController::class,'stats']);

    Route::get('/qr',[QrController::class,'qrView'])->name('qr');
    Route::post('/addQr',[QrController::class,'addQr']);
    Route::get('/deleteQr/{id}',[QrController::class,'deleteQr']);
    Route::get('/selectTable',[TillController::class,'selectTable'])->name('selectTable');

    Route::get('/getNotification',[OrderController::class,'getNotification']);
});
