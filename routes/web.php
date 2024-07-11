<?php

use App\Http\Controllers\LoginController;
use App\Http\Livewire\AccessModel;
use App\Http\Livewire\AccessPoint;
use App\Http\Livewire\Brand;
use App\Http\Livewire\Category;
use App\Http\Livewire\Company;
use App\Http\Livewire\Customer;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Dealer;
use App\Http\Livewire\InvoiceItem;
use App\Http\Livewire\Item;
use App\Http\Livewire\Measurement;
use App\Http\Livewire\Permission;
use App\Http\Livewire\PrintBarcode;
use App\Http\Livewire\PurchaseInvoice;
use App\Http\Livewire\Sale;
use App\Http\Livewire\Shop;
use App\Http\Livewire\StockTransfer;
use App\Http\Livewire\Users;
use App\Http\Livewire\UserType;

use App\Http\Livewire\DashboardPage;
use App\Http\Livewire\Bank;
use App\Http\Livewire\BranchStock;
use App\Http\Livewire\ExpenceType;
use App\Http\Livewire\Property;
use App\Http\Livewire\Expence;
use App\Http\Livewire\ExpenceReport;
use App\Http\Livewire\SalesReport;
use App\Http\Livewire\SalesSummary;
use App\Http\Livewire\ReturnHistoryReport;
use App\Http\Livewire\StockByProperty;
use App\Http\Livewire\Products;
use App\Http\Livewire\SalesView;
use App\Http\Livewire\InvoiceReturn;
use App\Http\Livewire\DealerCredit;
use App\Http\Livewire\SelectCounter;
use App\Http\Livewire\Counter;
use App\Http\Livewire\CounterActivityLog;
use App\Http\Livewire\IncomeType;
use App\Http\Livewire\Income;
use App\Http\Livewire\CounterCashoutHistory;
use App\Http\Livewire\Ledger;
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
Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth', 'access']], function () {
    // Route::group(['middleware'=>['auth']],function(){

    Route::get('/user-type', UserType::class)->name('user-type');
    Route::get('/access-model', AccessModel::class)->name('access-model');
    Route::get('/access-point/{id}', AccessPoint::class)->name('access-point');
    Route::get('/permission/{id}', Permission::class)->name('permission');
    Route::get('/users', Users::class)->name('users');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/brand', Brand::class)->name('brand');
    Route::get('/category', Category::class)->name('category');
    Route::get('/measurement', Measurement::class)->name('measurement');
    Route::get('/item', Item::class)->name('item');
    Route::get('/company', Company::class)->name('company');
    Route::get('/dealer', Dealer::class)->name('dealer');
    Route::get('/purchase-invoice', PurchaseInvoice::class)->name('purchase-invoice');
    Route::get('/invoice-items/{id}', InvoiceItem::class)->name('invoice-items');
    Route::get('/print-barcode/{id}', PrintBarcode::class)->name('print-barcode');
    Route::get('/stock-transfer/{id}', StockTransfer::class)->name('stock-transfer');
    Route::get('/sale/{id}', Sale::class)->name('sale');
    Route::get('/customer', Customer::class)->name('customer');
    Route::get('/shop', Shop::class)->name('shop');

    Route::get('/dash-board', DashboardPage::class)->name('dash-board');
    Route::get('/bank', Bank::class)->name('bank');
    Route::get('/expence-type', ExpenceType::class)->name('expence-type');
    Route::get('/expence', Expence::class)->name('expence');
    Route::get('/property', Property::class)->name('property');
    Route::get('/expence-report', ExpenceReport::class)->name('expence-report');
    Route::get('/sales-report', SalesReport::class)->name('sales-report');
    Route::get('/sales-summary', SalesSummary::class)->name('sales-summary');
    Route::get('/branch-stock', BranchStock::class)->name('branch-stock');
    Route::get('/return-report', ReturnHistoryReport::class)->name('return-report');
    Route::get('/stock-by-property/{id}', StockByProperty::class)->name('stock-by-property');
    Route::get('/products', Products::class)->name('products');
    Route::get('/sales-view', SalesView::class)->name('sales-view');
    Route::get('/invoice-return/{id}/{date}', InvoiceReturn::class)->name('invoice-return');
    Route::get('/dealer-credit', DealerCredit::class)->name('dealer-credit');
    Route::get('/select-counter', SelectCounter::class)->name('select-counter');
    Route::get('/counter', Counter::class)->name('counter');
    Route::get('/counters-activity-log', CounterActivityLog::class)->name('counters-activity-log');
    Route::get('/income-type', IncomeType::class)->name('income-type');
    Route::get('/income', Income::class)->name('income');
    Route::get('/counter-cashout-history', CounterCashoutHistory::class)->name('counter-cashout-history');
    Route::get('/ledger', Ledger::class)->name('ledger');
});

