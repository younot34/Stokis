<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\WarehouseProductController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KirimBarangController;
use App\Http\Controllers\Admin\CentralStockController;
use App\Http\Controllers\Warehouse\TransactionController;
use App\Http\Controllers\Warehouse\DashboardController as WarehouseDashboardController;
use App\Http\Controllers\Warehouse\StockController;

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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/redirect-after-login', function () {
    $user = auth()->user();
    if ($user->role == 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role == 'stokis') {
        return redirect()->route('warehouse.dashboard');
    } else {
        abort(403);
    }
})->middleware(['auth'])->name('redirect.after.login');

Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function(){

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // stokis
    Route::resource('warehouses', WarehouseController::class);

    // User
    Route::resource('users', UserController::class);

    // Kategori
    Route::resource('categories', CategoryController::class);

    // Produk
    Route::resource('products', ProductController::class);

    // Stok per stokis
    Route::get('stocks', [WarehouseProductController::class,'index'])->name('stocks.index');
    Route::post('stocks/{warehouse}/{product}', [WarehouseProductController::class,'updateStock'])->name('stocks.update');

    // Purchase Orders
    Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase_orders.index');
    Route::get('/purchase-orders/{poRecap}', [PurchaseOrderController::class, 'show'])->name('purchase_orders.show');
    Route::post('/purchase-orders/{purchaseOrder}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase_orders.approve');

    // Reports
    Route::get('reports/outgoing', [ReportController::class, 'outgoing'])->name('reports.outgoing');

    //stok pusat
    Route::resource('central_stocks', CentralStockController::class);

    //kirim barang ke stokis
    Route::resource('kirims', KirimBarangController::class);
    Route::get('/generate-kirim-code/{warehouse}', [KirimBarangController::class, 'generateCodeAjax'])->name('kirim.generate.code');

});

Route::middleware(['auth','role:stokis'])->prefix('warehouse')->name('warehouse.')->group(function(){

    // Dashboard
    Route::get('dashboard', [WarehouseDashboardController::class, 'index'])->name('dashboard');

    // Barang Keluar / Transaksi
    Route::get('transactions', [TransactionController::class,'index'])->name('transactions.index');
    Route::post('transactions', [TransactionController::class,'store'])->name('transactions.store');

    // Request PO
    Route::get('purchase-orders', [PurchaseOrderController::class,'indexWarehouse'])->name('purchase_orders.index');
    Route::get('purchase-orders/create', [PurchaseOrderController::class,'createWarehouse'])->name('purchase_orders.create');
    Route::post('purchase-orders', [PurchaseOrderController::class,'store'])->name('purchase_orders.store');
    Route::get('purchase-orders/recap/{po}', [PurchaseOrderController::class, 'showWarehouseRecap'])->name('purchase_orders.show');

    //Stok stokis
    Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
});


require __DIR__.'/auth.php';
