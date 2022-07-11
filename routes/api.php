<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SalesOrderController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\Api\TradeInController;
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
Route::get('/clear-route', function () {
    Artisan::call('route:cache');
    echo '<script>alert("route clear Success")</script>';
});
Route::get('/me', function () {
    echo "Api is working";
});
Route::post('login', [UserController::class, 'login'])->name('user.login');
Route::get('userList', [UserController::class, 'usersList'])->name('user.usersList');
Route::get('customers', [LeadController::class, 'getCustomers'])->name('user.customers');
   Route::post('/inProgressQuote', [QuoteController::class, 'inProgressQuote']);
   Route::post('/sentQuote', [QuoteController::class, 'sentQuote']);
    Route::post('/filterInProgressQuote', [QuoteController::class, 'filterInProgressQuote']);
    //Route::post('/searchInProgressQuote', [QuoteController::class, 'searchInProgressQuote']);
    //Route::post('sales_orders', [SalesOrderController::class, 'getSalesOrders'])->name('user.sales_orders');
    Route::post('/send_quote', [QuoteController::class, 'sendQuote']);
   // Route::post('/add-quote', [QuoteController::class, 'createQuoteWithExistingLead']);
    Route::post('/add-quote', [QuoteController::class, 'createQuoteWithNewLead']);
Route::post('/filterSentQuote', [QuoteController::class, 'filterSentQuote']);
    Route::post('/saveTradeIn', [TradeInController::class, 'saveTradeIn']);
    Route::post('/create_sales_order', [SalesOrderController::class, 'createSalesOrder']);

//using middleware
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('models', [ProductController::class, 'models'])->name('user.models');
    Route::get('dealerships', [ProductController::class, 'dealerships'])->name('user.dealerships');
    Route::post('products_by_dealer', [ProductController::class, 'productsByDealer'])->name('user.products_by_dealer');
    Route::get('used_equipments', [ProductController::class, 'usedEquipments'])->name('user.used_equipments');
    Route::get('coming_soon_equipments', [ProductController::class, 'comingSoonEquipments'])->name('user.coming_soon_equipments');
    Route::get('categories', [ProductController::class, 'categories'])->name('user.categories');
   //Route::get('customers', [LeadController::class, 'getCustomers'])->name('user.customers');
    Route::post('upload_machine', [ProductController::class, 'uploadMachine'])->name('user.upload_machine');
    Route::post('sales_calls', [LeadController::class, 'salesCalls'])->name('user.sales_calls');
    Route::post('detail_sales_call', [LeadController::class, 'detailSalesCall'])->name('user.detail_sales_call');
    Route::post('complete_sales_call', [LeadController::class, 'completeSalesCall'])->name('user.complete_sales_call');
    Route::post('sales_orders', [SalesOrderController::class, 'getSalesOrders'])->name('user.sales_orders');
    Route::post('/sign-out', [UserController::class, 'logout']);
  //  Route::post('/add-quote', [QuoteController::class, 'createQuoteWithExistingLead']);
    //Route::post('/inProgressQuote', [QuoteController::class, 'inProgressQuote']);
   // Route::post('/filterInProgressQuote', [QuoteController::class, 'filterInProgressQuote']);
    Route::post('/searchInProgressQuote', [QuoteController::class, 'searchInProgressQuote']);
    //Route::post('/sentQuote', [QuoteController::class, 'sentQuote']);
    //Route::post('/filterSentQuote', [QuoteController::class, 'filterSentQuote']);
    Route::post('/searchSentQuote', [QuoteController::class, 'searchSentQuote']);
   // Route::post('/send_quote', [QuoteController::class, 'sendQuote']);
   // Route::post('/create_sales_order', [SalesOrderController::class, 'createSalesOrder']);
    Route::post('/filter_sales_order', [SalesOrderController::class, 'filterSalesOrder']);
   // Route::post('/saveTradeIn', [TradeInController::class, 'saveTradeIn']);
    
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


