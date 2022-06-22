<?php

use App\Http\Controllers\Admin\LoginController;

use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\CmsController;
use App\Http\Controllers\Front\BrandController;
use App\Http\Controllers\Front\PartsController;
use App\Http\Controllers\Front\NewsController as NewsC;
use App\Http\Controllers\Front\ProductController as ProductC;
use App\Http\Controllers\Front\ContactController as ContactC;
use App\Http\Controllers\Front\ServiceController as ServiceC;


use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\SubAdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DealerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\QuoteController;
use App\Http\Controllers\Admin\SalesOrderController;
use App\Http\Controllers\Admin\TradeInController;
use App\Http\Controllers\Admin\SalesCallController;
use App\Http\Controllers\Admin\SalesOrderReportController;
use App\Http\Controllers\Admin\StockReportController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\PartController;
use App\Http\Controllers\Admin\ServiceController;



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


// Route::POST('dologin', 'Admin\LoginController@postLogin');

Route::get('mail_test', function () {
    \Mail::raw('Hello World fjs!', function($msg) {$msg->to('ajaykant.kanojiya@graffersid.com')->subject('Test Email'); });
          dd('mail send');
});



Auth::routes();




Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    echo '<h1>cache clear Success123456lt<h1>';
});

Route::get('/config-cache', function () {
    Artisan::call('config:cache');
    echo '<h1>cache clear Success123456<h1>';
});

// Route::get('/clear-cache', function () {
//     Artisan::call('session:table');
//     echo '<h1>session table created<h1>';
// });

Route::get('/clear-config', function () {
    Artisan::call('config:cache');
    echo '<h1>config cache Success</h1>';
});
Route::get('/clear-view', function () {
    Artisan::call('view:clear');
    echo '<script>alert("view clear Success")</script>';
});
Route::get('/clear-route', function () {
    Artisan::call('route:cache');
    echo '<script>alert("route clear Success")</script>';
});
Route::get('/clear-store', function () {
    Artisan::call('storage:link');
    echo '<script>alert("linked")</script>';
});

Route::get('admin/login', [App\Http\Controllers\Admin\LoginController::class, 'index'])->name('admin.login');
// Route::get('/admin/login', array('as'=>'admin.login','uses' => 'Admin\LoginController@index'));
//  Route::POST('/admin/dologin', 'Admin\LoginController@postLogin');
Route::POST('/admin/dologin', [App\Http\Controllers\Admin\LoginController::class, 'postLogin']);
//  Route::POST('dologin', 'Admin\LoginController@postLogin');

// Route::get('/admin/dologin', function () {
//     Artisan::call('storage:link');
//     echo '<script>alert("linked")</script>';
// });

 Route::group(['prefix'=> 'admin','middleware' => ['auth']] , function(){
	// Route::get('dashboard', ['as'=>'admin.dashboard', 'uses'=>'Admin\DashboardController@index']);sub_admin
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
	// Route::get('/logout', ['as'=>'admin.logout', 'uses'=>'Admin\LoginController@getLogout']);
    Route::get('logout', [LoginController::class, 'getLogout'])->name('admin.logout');
});

Route::group(['prefix'=> 'admin','middleware' => ['auth','UserRole']] , function(){
	Route::get('/settings', ['as'=>'admin.settings', 'uses'=>'Admin\DashboardController@setting']);

	/*actions Start*/
	Route::get('actions/index',['as'=>'actions.index','uses'=>'Admin\ModulesController@index']);
	Route::get('actions/add',['as'=>'actions.add','uses'=>'Admin\ModulesController@actionsAdd']);
	Route::POST('actions/save',['as'=>'actions.save','uses'=>'Admin\ModulesController@actionsSave']);
	Route::get('actions/edit/{id}',['as'=>'actions.edit','uses'=>'Admin\ModulesController@edit']);
	Route::POST('actions/update',['as'=>'actions.update','uses'=>'Admin\ModulesController@update']);
	Route::get('actions/delete/{id}',['as'=>'actions.delete','uses'=>'Admin\ModulesController@delete']);
	/*actions End*/

	/*Section Start*/
	Route::get('sections/index',['as'=>'sections.index','uses'=>'Admin\ModulesController@sectionsList']);
	Route::get('sections/add',['as'=>'sections.add','uses'=>'Admin\ModulesController@sectionsAdd']);
	Route::POST('sections/save',['as'=>'sections.save','uses'=>'Admin\ModulesController@sectionsSave']);
	Route::get('sections/edit/{id}',['as'=>'sections.edit','uses'=>'Admin\ModulesController@sectionsEdit']);
	Route::POST('sections/update',['as'=>'sections.update','uses'=>'Admin\ModulesController@sectionsUpdate']);
	Route::get('sections/delete/{id}',['as'=>'sections.delete','uses'=>'Admin\ModulesController@sectionsDelete']);
	/*Section End*/

	/*Roles Start*/
	Route::get('roles/index',['as'=>'roles.index','uses'=>'Admin\ModulesController@rolesList']);
	Route::get('roles/add',['as'=>'roles.add','uses'=>'Admin\ModulesController@rolesAdd']);
	Route::POST('roles/save',['as'=>'roles.save','uses'=>'Admin\ModulesController@rolesSave']);
	Route::get('roles/edit/{id}',['as'=>'roles.edit','uses'=>'Admin\ModulesController@rolesEdit']);
	Route::POST('roles/update',['as'=>'roles.update','uses'=>'Admin\ModulesController@rolesUpdate']);
	Route::get('roles/delete/{id}',['as'=>'roles.delete','uses'=>'Admin\ModulesController@rolesDelete']);
	/*Roles End*/

    /// Sub Admin start ////
    // Route::get('sub_admin/index', ['as'=>'sub_admin.index',  'uses'=>'Admin\SubAdminController@index']);
    Route::get('sub_admin/index', [SubAdminController::class, 'index'])->name('sub_admin.index');

	// Route::get('sub_admin/add', ['as'=>'sub_admin.add',  'uses'=>'Admin\SubAdminController@add']);
    Route::get('sub_admin/add', [SubAdminController::class, 'add'])->name('sub_admin.add');

	// Route::POST('sub_admin/save', ['as'=>'sub_admin.save',  'uses'=>'Admin\SubAdminController@save']);
    Route::POST('sub_admin/save', [SubAdminController::class, 'save'])->name('sub_admin.save');

	// Route::get('sub_admin/edit/{id}', ['as'=>'sub_admin.edit',  'uses'=>'Admin\SubAdminController@edit']);
    Route::get('sub_admin/edit/{id}', [SubAdminController::class, 'edit'])->name('sub_admin.edit');

	// Route::POST('sub_admin/update', ['as'=>'sub_admin.update','uses'=>'Admin\SubAdminController@update']);
    Route::POST('sub_admin/update', [SubAdminController::class, 'update'])->name('sub_admin.update');

	// Route::get('sub_admin/delete/{id}', ['as'=>'sub_admin.delete', 'uses'=>'Admin\SubAdminController@delete']);
    Route::get('sub_admin/delete/{id}', [SubAdminController::class, 'delete'])->name('sub_admin.delete');

	// Route::get('sub_admin/set-status/{id}', ['as'=>'sub_admin.status', 'uses'=>'Admin\SubAdminController@set_status']);
    Route::get('sub_admin/set-status/{id}', [SubAdminController::class, 'set-status'])->name('sub_admin.status');
     //end subadmin //

  	/// Admin User ////
    // Route::get('users/index', ['as'=>'users.index',  'uses'=>'Admin\UserController@index']);
     Route::get('users/index', [UserController::class, 'index'])->name('users.index');

    // Route::get('users/add', ['as'=>'users.add', 'uses'=>'Admin\UserController@add']);
    Route::get('users/add', [UserController::class, 'add'])->name('users.add');

    // Route::POST('users/save', ['as'=>'users.save', 'uses'=>'Admin\UserController@save']);
    Route::POST('users/save', [UserController::class, 'save'])->name('users.save');


    // Route::get('users/edit/{id}', ['as'=>'users.edit', 'uses'=>'Admin\UserController@edit']);
     Route::get('users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');

    // Route::POST('users/update', ['as'=>'users.update', 'uses'=>'Admin\UserController@update']);
    Route::POST('users/update', [UserController::class, 'update'])->name('users.update');

    // Route::get('users/delete/{id}', ['as'=>'users.delete', 'uses'=>'Admin\UserController@delete']);
    Route::get('users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');

    // Route::get('users/view/{id}', ['as'=>'users.view', 'uses'=>'Admin\UserController@view']);
    Route::get('users/view/{id}', [UserController::class, 'view'])->name('users.view');

    // Route::get('users/password/{id}', ['as'=>'users.password', 'uses'=>'Admin\UserController@reset_password']);
    Route::get('users/password/{id}', [UserController::class, 'reset_password'])->name('users.password');

    // Route::POST('users/password/save', ['as'=>'users.password.save', 'uses'=>'Admin\UserController@savePassword']);
    Route::POST('users/password/save', [UserController::class, 'savePassword'])->name('users.password.save');

    // Route::get('users/status/{id}', ['as'=>'users.status', 'uses'=>'Admin\UserController@status']);
    Route::get('users/status/{id}', [UserController::class, 'status'])->name('users.status');

    // Route::get('users/view/{id}', ['as'=>'users.view', 'uses'=>'Admin\UserController@view']);
    Route::get('users/view/{id}', [UserController::class, 'view'])->name('users.view');
     //end users //

    /// Categories ////
    // Route::get('categories/index', ['as'=>'categories.index',  'uses'=>'Admin\CategoryController@index']);
    Route::get('categories/index', [CategoryController::class, 'index'])->name('categories.index');

    // Route::get('categories/add', ['as'=>'categories.add',  'uses'=>'Admin\CategoryController@add']);
    Route::get('categories/add', [CategoryController::class, 'add'])->name('categories.add');

    // Route::POST('categories/save', ['as'=>'categories.save',  'uses'=>'Admin\CategoryController@save']);
    Route::POST('categories/save', [CategoryController::class, 'save'])->name('categories.save');

    // Route::get('categories/edit/{id}', ['as'=>'categories.edit', 'uses'=>'Admin\CategoryController@edit']);
    Route::get('categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');

    // Route::POST('categories/update', ['as'=>'categories.update', 'uses'=>'Admin\CategoryController@update']);
    Route::POST('categories/update', [CategoryController::class, 'update'])->name('categories.update');

    // Route::get('categories/delete/{id}', ['as'=>'categories.delete', 'uses'=>'Admin\CategoryController@delete']);
    Route::get('categories/delete/{id}', [CategoryController::class, 'delete'])->name('categories.delete');

    // Route::get('categories/status/{id}', ['as'=>'categories.status', 'uses'=>'Admin\CategoryController@status']);
    Route::get('categories/status/{id}', [CategoryController::class, 'status'])->name('categories.status');


    /// Dealers ////
    // Route::get('dealers/index', ['as'=>'dealers.index',  'uses'=>'Admin\DealerController@index']);
    Route::get('dealers/index', [DealerController::class, 'index'])->name('dealers.index');

    // Route::get('dealers/add', ['as'=>'dealers.add',  'uses'=>'Admin\DealerController@add']);
    Route::get('dealers/add', [DealerController::class, 'add'])->name('dealers.add');

    // Route::POST('dealers/save', ['as'=>'dealers.save',  'uses'=>'Admin\DealerController@save']);
    Route::POST('dealers/save', [DealerController::class, 'save'])->name('dealers.save');

    // Route::get('dealers/edit/{id}', ['as'=>'dealers.edit', 'uses'=>'Admin\DealerController@edit']);
    Route::get('dealers/edit/{id}', [DealerController::class, 'edit'])->name('dealers.edit');

    // Route::POST('dealers/update', ['as'=>'dealers.update', 'uses'=>'Admin\DealerController@update']);
    Route::POST('dealers/update', [DealerController::class, 'update'])->name('dealers.update');

    // Route::POST('dealers/sortBrands', ['as'=>'dealers.sortBrands', 'uses'=>'Admin\DealerController@sortBrands']);
    Route::POST('dealers/sortBrands', [DealerController::class, 'sortBrands'])->name('dealers.sortBrands');

    // Route::get('dealers/delete/{id}', ['as'=>'dealers.delete', 'uses'=>'Admin\DealerController@delete']);
    Route::get('dealers/delete/{id}', [DealerController::class, 'delete'])->name('dealers.delete');

    // Route::get('dealers/status/{id}', ['as'=>'dealers.status', 'uses'=>'Admin\DealerController@status']);
     Route::get('dealers/status/{id}', [DealerController::class, 'status'])->name('dealers.status');


    /// products ////
    // Route::get('products/index', ['as'=>'products.index',  'uses'=>'Admin\ProductController@index']);
    Route::get('products/index', [ProductController::class, 'index'])->name('products.index');

    // Route::get('products/add', ['as'=>'products.add',  'uses'=>'Admin\ProductController@add']);
    Route::get('products/add', [ProductController::class, 'add'])->name('products.add');

    // Route::POST('products/save', ['as'=>'products.save',  'uses'=>'Admin\ProductController@save']);
    Route::POST('products/save', [ProductController::class, 'save'])->name('products.save');

    // Route::get('products/edit/{id}', ['as'=>'products.edit', 'uses'=>'Admin\ProductController@edit']);
    Route::get('products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
    
    // Route::POST('products/update', ['as'=>'products.update', 'uses'=>'Admin\ProductController@update']);
    Route::POST('products/update', [ProductController::class, 'update'])->name('products.update');

    // Route::get('products/delete/{id}', ['as'=>'products.delete', 'uses'=>'Admin\ProductController@delete']);
    Route::get('products/delete/{id}', [ProductController::class, 'delete'])->name('products.delete');

    // Route::get('products/status/{id}', ['as'=>'products.status', 'uses'=>'Admin\ProductController@status']);
    Route::get('products/status/{id}', [ProductController::class, 'status'])->name('products.status');

    // Route::get('products/image/delete/{id}', ['as'=>'products.image.delete', 'uses'=>'Admin\ProductController@imageDelete']);
    Route::get('products/image/delete/{id}', [ProductController::class, 'imageDelete'])->name('products.image.delete');

    // Route::POST('products/sortProducts', ['as'=>'products.sortProducts', 'uses'=>'Admin\ProductController@sortProducts']);
    Route::POST('products/sortProducts', [ProductController::class, 'sortProducts'])->name('products.sortProducts');

    // Route::POST('products/add_more', ['as'=>'products.add_more', 'uses'=>'Admin\ProductController@add_more']);
    Route::POST('products/add_more', [ProductController::class, 'add_more'])->name('products.add_more');

    /// Customers ////
    // Route::get('customers/index', ['as'=>'customers.index',  'uses'=>'Admin\CustomerController@index']);
    Route::get('customers/index', [CustomerController::class, 'index'])->name('customers.index');

    // Route::get('customers/add', ['as'=>'customers.add',  'uses'=>'Admin\CustomerController@add']);
      Route::get('customers/add', [CustomerController::class, 'add'])->name('customers.add');

    // Route::POST('customers/save', ['as'=>'customers.save',  'uses'=>'Admin\CustomerController@save']);
     Route::POST('customers/save', [CustomerController::class, 'save'])->name('customers.save');

    // Route::get('customers/edit/{id}', ['as'=>'customers.edit', 'uses'=>'Admin\CustomerController@edit']);
     Route::get('customers/edit/{id}', [CustomerController::class, 'edit'])->name('customers.edit');

    // Route::POST('customers/update', ['as'=>'customers.update', 'uses'=>'Admin\CustomerController@update']);
    Route::POST('customers/update', [CustomerController::class, 'update'])->name('customers.update');

    // Route::get('customers/delete/{id}', ['as'=>'customers.delete', 'uses'=>'Admin\CustomerController@delete']);
    Route::get('customers/delete/{id}', [CustomerController::class, 'delete'])->name('customers.delete');

    // Route::get('customers/status/{id}', ['as'=>'customers.status', 'uses'=>'Admin\CustomerController@status']);
    Route::get('customers/status/{id}', [CustomerController::class, 'status'])->name('customers.status');

    // Route::any('customers/import', ['as'=>'customers.import', 'uses'=>'Admin\CustomerController@import']);
    Route::any('customers/import', [CustomerController::class, 'import'])->name('customers.import');

    /// leads ////
    // Route::get('leads/index', ['as'=>'leads.index',  'uses'=>'Admin\LeadController@index']);
    Route::get('leads/index', [LeadController::class, 'index'])->name('leads.index');

    // Route::get('leads/add', ['as'=>'leads.add',  'uses'=>'Admin\LeadController@add']);
    Route::get('leads/add', [LeadController::class, 'add'])->name('leads.add');

    // Route::POST('leads/save', ['as'=>'leads.save',  'uses'=>'Admin\LeadController@save']);
    Route::POST('leads/save', [LeadController::class, 'save'])->name('leads.save');

    // Route::get('leads/edit/{id}', ['as'=>'leads.edit', 'uses'=>'Admin\LeadController@edit']);
    Route::get('leads/edit/{id}', [LeadController::class, 'edit'])->name('leads.edit');

    // Route::POST('leads/update', ['as'=>'leads.update', 'uses'=>'Admin\LeadController@update']);
    Route::POST('leads/update', [LeadController::class, 'update'])->name('leads.update');

    // Route::get('leads/view/{id}', ['as'=>'leads.view', 'uses'=>'Admin\LeadController@view']);
    Route::get('leads/view/{id}', [LeadController::class, 'view'])->name('leads.view');

    // Route::get('leads/delete/{id}', ['as'=>'leads.delete', 'uses'=>'Admin\LeadController@delete']);
     Route::get('leads/delete/{id}', [LeadController::class, 'delete'])->name('leads.delete');
    
    // Route::POST('leads/comment', ['as'=>'leads.comment',  'uses'=>'Admin\LeadController@comment']);
    Route::POST('leads/comment', [LeadController::class, 'comment'])->name('leads.comment');


    /// quotes ////
    // Route::get('quotes/index', ['as'=>'quotes.index',  'uses'=>'Admin\QuoteController@index']);
    Route::get('quotes/index', [QuoteController::class, 'index'])->name('quotes.index');

    // Route::get('quotes/view/{id}', ['as'=>'quotes.view', 'uses'=>'Admin\QuoteController@view']);
    Route::get('quotes/view/{id}', [QuoteController::class, 'view'])->name('quotes.view');

    // Route::get('quotes/edit/{id}', ['as'=>'quotes.edit', 'uses'=>'Admin\QuoteController@edit']);
    Route::get('quotes/edit/{id}', [QuoteController::class, 'edit'])->name('quotes.edit');

    // Route::POST('quotes/update', ['as'=>'quotes.update', 'uses'=>'Admin\QuoteController@update']);
    Route::POST('quotes/update', [QuoteController::class, 'update'])->name('quotes.update');

    // Route::POST('quotes/removeMachine', ['as'=>'quotes.removeMachine', 'uses'=>'Admin\QuoteController@removeMachine']);
    Route::POST('quotes/removeMachine', [QuoteController::class, 'removeMachine'])->name('quotes.removeMachine');

    // Route::POST('quotes/addExtra', ['as'=>'quotes.addExtra', 'uses'=>'Admin\QuoteController@addExtra']);
    Route::POST('quotes/addExtra', [QuoteController::class, 'addExtra'])->name('quotes.addExtra');

    // Route::get('quotes/getProducts/{id}/{type}/{dealer_id}', ['as'=>'quotes.getProducts', 'uses'=>'Admin\QuoteController@getProducts']);
    Route::get('quotes/getProducts/{id}/{type}/{dealer_id}', [QuoteController::class, 'getProducts'])->name('quotes.getProducts');

    // Route::POST('quotes/addMachine', ['as'=>'quotes.addMachine', 'uses'=>'Admin\QuoteController@addMachine']);
    Route::POST('quotes/addMachine', [QuoteController::class, 'addMachine'])->name('quotes.addMachine');

    // Route::POST('quotes/update', ['as'=>'quotes.update', 'uses'=>'Admin\QuoteController@update']);
    Route::POST('quotes/update', [QuoteController::class, 'update'])->name('quotes.update');

    // Route::get('quotes/resend/{id}', ['as'=>'quotes.resend', 'uses'=>'Admin\QuoteController@resend']);
    Route::get('quotes/resend/{id}', [QuoteController::class, 'resend'])->name('quotes.resend');


    /// sales_order ////
    // Route::get('sales_order/index', ['as'=>'sales_order.index',  'uses'=>'Admin\SalesOrderController@index']);
    Route::get('sales_order/index', [SalesOrderController::class, 'index'])->name('sales_order.index');

    // Route::get('sales_order/view/{id}', ['as'=>'sales_order.view', 'uses'=>'Admin\SalesOrderController@view']);
    Route::get('sales_order/view/{id}', [SalesOrderController::class, 'view'])->name('sales_order.view');

//    Route::get('sales_order/edit/{id}', ['as'=>'sales_order.edit', 'uses'=>'Admin\SalesOrderController@edit']);
     Route::get('sales_order/edit/{id}', [SalesOrderController::class, 'edit'])->name('sales_order.edit');

    // Route::POST('sales_order/update', ['as'=>'sales_order.update', 'uses'=>'Admin\SalesOrderController@update']);
    Route::POST('sales_order/update', [SalesOrderController::class, 'update'])->name('sales_order.update');

    // Route::get('sales_order/status/{id}', ['as'=>'sales_order.status', 'uses'=>'Admin\SalesOrderController@status']);
    Route::get('sales_order/status/{id}', [SalesOrderController::class, 'status'])->name('sales_order.status');

    // Route::POST('sales_order/add_machine', ['as'=>'sales_order.add_machine', 'uses'=>'Admin\SalesOrderController@add_machine']);
    Route::POST('sales_order/add_machine', [SalesOrderController::class, 'add_machine'])->name('sales_order.add_machine');

    // Route::get('sales_order/getMakes/{id}', ['as'=>'sales_order.getMakes', 'uses'=>'Admin\SalesOrderController@getMakes']);
    Route::get('sales_order/getMakes/{id}', [SalesOrderController::class, 'getMakes'])->name('sales_order.getMakes');

    // Route::get('sales_order/getSerialNumbers/{name}', ['as'=>'sales_order.getSerialNumbers', 'uses'=>'Admin\SalesOrderController@getSerialNumbers']);
    Route::get('sales_order/getSerialNumbers/{name}', [SalesOrderController::class, 'getSerialNumbers'])->name('sales_order.getSerialNumbers');
  
    // Route::get('sales_order/getModels/{id}/{selected?}', ['as'=>'sales_order.getModels', 'uses'=>'Admin\SalesOrderController@getModels']);
     Route::get('sales_order/getModels/{id}{selected}', [SalesOrderController::class, 'getModels'])->name('sales_order.getModels');

    /// Trade In ////
    // Route::get('trade_in/index', ['as'=>'trade_in.index',  'uses'=>'Admin\TradeInController@index']);
    Route::get('trade_in/index', [TradeInController::class, 'index'])->name('trade_in.index');

    // Route::get('trade_in/view/{id}', ['as'=>'trade_in.view', 'uses'=>'Admin\TradeInController@view']);
    Route::get('trade_in/view/{id}', [TradeInController::class, 'view'])->name('trade_in.view');


    /// sales_calls ////
    // Route::any('sales_calls_report/index', ['as'=>'sales_calls_report.index',  'uses'=>'Admin\SalesCallController@index']);
    Route::any('sales_calls_report/index', [SalesCallController::class, 'index'])->name('sales_calls_report.index');

    // Route::get('sales_calls_report/view/{id}', ['as'=>'sales_calls_report.view', 'uses'=>'Admin\SalesCallController@view']);
    Route::get('sales_calls_report/view/{id}', [SalesCallController::class, 'view'])->name('sales_calls_report.view');


    /// sales_order_report ////
    // Route::any('sales_order_report/index', ['as'=>'sales_order_report.index',  'uses'=>'Admin\SalesOrderReportController@index']);
    Route::any('sales_order_report/index', [SalesOrderReportController::class, 'index'])->name('sales_order_report.index');

    // Route::get('sales_order_report/view/{id}', ['as'=>'sales_order_report.view', 'uses'=>'Admin\SalesOrderReportController@view']);
    // Route::any('sales_order_report/view/{id}', [SalesOrderReportController::class, 'view'])->name('sales_order_report.view');

    /// stock_report ////
    // Route::any('stock_report/index', ['as'=>'stock_report.index',  'uses'=>'Admin\StockReportController@index']);
     Route::any('stock_report/index', [StockReportController::class, 'index'])->name('stock_report.index');

    /// Gallery ////
    // Route::get('gallery/index', ['as'=>'gallery.index',  'uses'=>'Admin\GalleryController@index']);
    Route::get('gallery/index', [GalleryController::class, 'index'])->name('gallery.index');
    // Route::get('gallery/add', ['as'=>'gallery.add',  'uses'=>'Admin\GalleryController@add']);
    Route::get('gallery/add', [GalleryController::class, 'add'])->name('gallery.add');
    
    // Route::POST('gallery/save', ['as'=>'gallery.save',  'uses'=>'Admin\GalleryController@save']);
    Route::POST('gallery/save', [GalleryController::class, 'save'])->name('gallery.save');
    // Route::get('gallery/edit/{id}', ['as'=>'gallery.edit', 'uses'=>'Admin\GalleryController@edit']);
    Route::get('gallery/edit/{id}', [GalleryController::class, 'edit'])->name('gallery.edit');

    // Route::POST('gallery/update', ['as'=>'gallery.update', 'uses'=>'Admin\GalleryController@update']);
    Route::POST('gallery/update', [GalleryController::class, 'update'])->name('gallery.update');
    // Route::get('gallery/delete/{id}', ['as'=>'gallery.delete', 'uses'=>'Admin\GalleryController@delete']);
    Route::get('gallery/delete/{id}', [GalleryController::class, 'delete'])->name('gallery.delete');
    // Route::get('gallery/status/{id}', ['as'=>'gallery.status', 'uses'=>'Admin\GalleryController@status']);
    Route::get('gallery/status/{id}', [GalleryController::class, 'status'])->name('gallery.status');

    /// Team ////
    // Route::get('team/index', ['as'=>'team.index',  'uses'=>'Admin\TeamController@index']);
    Route::get('team/index', [TeamController::class, 'index'])->name('team.index');
    // Route::get('team/add', ['as'=>'team.add',  'uses'=>'Admin\TeamController@add']);
    Route::get('team/add', [TeamController::class, 'add'])->name('team.add');
    // Route::POST('team/save', ['as'=>'team.save',  'uses'=>'Admin\TeamController@save']);
    Route::POST('team/save', [TeamController::class, 'save'])->name('team.save');
    // Route::get('team/edit/{id}', ['as'=>'team.edit', 'uses'=>'Admin\TeamController@edit']);
    Route::get('team/edit/{id}', [TeamController::class, 'edit'])->name('team.edit');
    // Route::POST('team/update', ['as'=>'team.update', 'uses'=>'Admin\TeamController@update']);
    Route::POST('team/update', [TeamController::class, 'update'])->name('team.update');
    // Route::get('team/delete/{id}', ['as'=>'team.delete', 'uses'=>'Admin\TeamController@delete']);
    Route::get('team/delete/{id}', [TeamController::class, 'delete'])->name('team.delete');
    // Route::get('team/status/{id}', ['as'=>'team.status', 'uses'=>'Admin\TeamController@status']);
    Route::get('team/status/{id}', [TeamController::class, 'status'])->name('team.status');


    /// News ////
    // Route::get('news/index', ['as'=>'news.index',  'uses'=>'Admin\NewsController@index']);
    Route::get('news/index', [NewsController::class, 'index'])->name('news.index');
    // Route::get('news/add', ['as'=>'news.add',  'uses'=>'Admin\NewsController@add']);
    Route::get('news/add', [NewsController::class, 'add'])->name('news.add');

    // Route::POST('news/save', ['as'=>'news.save',  'uses'=>'Admin\NewsController@save']);
    Route::POST('news/save', [NewsController::class, 'save'])->name('news.save');

    // Route::get('news/edit/{id}', ['as'=>'news.edit', 'uses'=>'Admin\NewsController@edit']);
      Route::get('news/edit/{id}', [NewsController::class, 'edit'])->name('news.edit');

    // Route::POST('news/update', ['as'=>'news.update', 'uses'=>'Admin\NewsController@update']);
     Route::POST('news/update', [NewsController::class, 'update'])->name('news.update');

    // Route::get('news/delete/{id}', ['as'=>'news.delete', 'uses'=>'Admin\NewsController@delete']);
    Route::get('news/delete/{id}', [NewsController::class, 'delete'])->name('news.delete');

    // Route::get('news/status/{id}', ['as'=>'news.status', 'uses'=>'Admin\NewsController@status']);
     Route::get('news/status/{id}', [NewsController::class, 'status'])->name('news.status');


    /// Contacts ////
    // Route::get('contacts/index', ['as'=>'contacts.index',  'uses'=>'Admin\ContactController@index']);
     Route::get('contacts/index', [ContactController::class, 'index'])->name('contacts.index');

    // Route::get('contacts/view/{id}', ['as'=>'contacts.view', 'uses'=>'Admin\ContactController@view']);
    Route::get('contacts/view/{id}', [ContactController::class, 'view'])->name('contacts.view');

    // Route::get('contacts/delete/{id}', ['as'=>'contacts.delete', 'uses'=>'Admin\ContactController@delete']);
    Route::get('contacts/delete/{id}', [ContactController::class, 'delete'])->name('contacts.delete');

    /// Parts Requests ////
    // Route::get('parts/index', ['as'=>'parts.index',  'uses'=>'Admin\PartController@index']);
    Route::get('parts/index', [PartController::class, 'index'])->name('parts.index');

    // Route::get('parts/view/{id}', ['as'=>'parts.view', 'uses'=>'Admin\PartController@view']);
      Route::get('parts/view/{id}', [PartController::class, 'view'])->name('parts.view');

    /// Service Requests ////
    // Route::get('services/index', ['as'=>'services.index',  'uses'=>'Admin\ServiceController@index']);
    Route::get('services/index', [ServiceController::class, 'index'])->name('services.index');

    // Route::get('services/view/{id}', ['as'=>'services.view', 'uses'=>'Admin\ServiceController@view']);
     Route::get('services/view/{id}', [ServiceController::class, 'view'])->name('services.view');



});


//=============================================================================================
/*Front Routes*/

Route::group(['namespace' => 'Front'], function(){

    /*Home*/
    Route::get('/', [HomeController::class, 'index'])->name('home');
    // Route::get('/',['as'=>'home','uses'=>'HomeController@index']);

    // Route::get('getModels/{id}',['as'=>'getModels','uses'=>'HomeController@getModels']);
    Route::get('getModels/{id}', [HomeController::class, 'getModels'])->name('getModels');

    // Route::get('about_us',['as'=>'about_us','uses'=>'CmsController@about_us']);
    Route::get('about_us', [CmsController::class, 'about_us'])->name('about_us');

    // Route::get('services',['as'=>'services','uses'=>'CmsController@services']);
    Route::get('services', [CmsController::class, 'services'])->name('services');

    // Route::get('our_team',['as'=>'our_team','uses'=>'CmsController@our_team']);
     Route::get('our_team', [CmsController::class, 'our_team'])->name('our_team');

    // Route::get('privacy_policy',['as'=>'privacy_policy','uses'=>'CmsController@privacy_policy']);
    Route::get('privacy_policy', [CmsController::class, 'privacy_policy'])->name('privacy_policy');


    /*Brands*/
    // Route::get('brand/{name}',['as'=>'brand','uses'=>'BrandController@index']);
    Route::get('brand/{name}', [BrandController::class, 'index'])->name('brand');

    /*News*/
    // Route::get('news',['as'=>'news','uses'=>'NewsController@index']);
      Route::get('news', [NewsC::class, 'index'])->name('news');

    // Route::get('news/details/{id}',['as'=>'news.details','uses'=>'NewsController@details']);
      Route::get('news/details/{id}', [NewsC::class, 'details'])->name('news.details');

    /*Products*/
    // Route::get('getProducts/{type}',['as'=>'getProducts','uses'=>'ProductController@getProducts']);
    Route::get('getProducts/{type}', [ProductC::class, 'getProducts'])->name('getProducts');

    // Route::get('productsByCategory/{id}',['as'=>'productsByCategory','uses'=>'ProductController@productsByCategory']);
    Route::get('productsByCategory/{id}', [ProductC::class, 'productsByCategory'])->name('productsByCategory');

    // Route::get('productDetails/{id}',['as'=>'productDetails','uses'=>'ProductController@productDetails']);
    Route::get('productDetails/{id}', [ProductC::class, 'productDetails'])->name('productDetails');

    // Route::get('productsFilter',['as'=>'productsFilter','uses'=>'ProductController@productsFilter']);
      Route::get('productsFilter', [ProductC::class, 'productsFilter'])->name('productsFilter');

    // Route::get('products/getModels/{id}/{selected?}',['as'=>'products.getModels','uses'=>'ProductController@getModels']);
    Route::get('products/getModels/{id}/{selected?}', [ProductC::class, 'getModels'])->name('products.getModels');

    /*Contact Us*/
    // Route::get('contact_us',['as'=>'contact_us','uses'=>'ContactController@contact_us']);
    Route::get('contact_us', [ContactC::class, 'contact_us'])->name('contact_us');
    
    // Route::POST('contact/save',['as'=>'contact.save','uses'=>'ContactController@contactSave']);
    Route::POST('contact/save', [ContactC::class, 'contactSave'])->name('contact.save');


    /*Request a service*/
    // Route::POST('request/service',['as'=>'request.service','uses'=>'ServiceController@requestService']);
    Route::POST('request/service', [ServiceC::class, 'requestService'])->name('request.service');

    /*Parts*/
    // Route::get('parts',['as'=>'parts','uses'=>'PartsController@index']);
      Route::get('parts', [PartsController::class, 'index'])->name('parts');

    // Route::POST('partsRequest',['as'=>'partsRequest','uses'=>'PartsController@partsRequest']);
    Route::POST('partsRequest', [PartsController::class, 'partsRequest'])->name('partsRequest');

    // Route::get('getModels/{id}',['as'=>'getModels','uses'=>'PartsController@getModels']);
      Route::get('getModels/{id}', [PartsController::class, 'getModels'])->name('getModels');
      

});

