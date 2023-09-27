<?php

use App\Http\Controllers\WebHomeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SimcardController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\UnionOrdersController;
use App\Http\Controllers\SpLogController;
use App\Http\Controllers\UnionMembershipController;
use App\Http\Controllers\PdfGenerateController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DocumentsController;
use Illuminate\Support\Facades\Auth;
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

// Home
Route::get('/', [WebHomeController::class, 'index'])->name('webindex');

// Route::middleware('auth')->get('/', function () {
//     return redirect()->route('login');
// });

// Profile Routes
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function(){
    Route::get('/', [HomeController::class, 'getProfile'])->name('detail');
    Route::post('/update', [HomeController::class, 'updateProfile'])->name('update');
    Route::post('/change-password', [HomeController::class, 'changePassword'])->name('change-password');
});

// Roles
Route::resource('roles', App\Http\Controllers\RolesController::class);

// Permissions
Route::resource('permissions', App\Http\Controllers\PermissionsController::class);

// Users 
Route::middleware('auth')->prefix('admin/users')->name('users.')->group(function(){
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
    Route::put('/update/{user}', [UserController::class, 'update'])->name('update');
    //Route::delete('/delete/{user}', [UserController::class, 'delete'])->name('destroy');
    Route::post('/delete', [UserController::class, 'delete'])->name('destroy');
    Route::get('/update/status/{user_id}/{status}', [UserController::class, 'updateStatus'])->name('status');

    
    Route::get('/import-users', [UserController::class, 'importUsers'])->name('import');
    Route::post('/upload-users', [UserController::class, 'uploadUsers'])->name('upload');

    Route::get('export/', [UserController::class, 'export'])->name('export');

    Route::get('test_acces/', [UserController::class, 'test_acces'])->name('test_acces');

});

//Route::middleware('auth')->prefix('banner')->name('banner.')->group(function(){
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function(){

    // product

    Route::get('product-list/', [ProductsController::class, 'index'])->name('productlist');
    Route::post('product-add/', [ProductsController::class, 'store'])->name('productadd');
    Route::post('product-update/', [ProductsController::class, 'update'])->name('productupdate');
    Route::post('product-update-stock-by-vendor/', [ProductsController::class, 'updateSalePriceAndStock'])->name('productupdatestock');
    Route::post('product-view-stock-by-vendor/', [ProductsController::class, 'viewSalePriceAndStock'])->name('productviewstock');
    Route::post('product-delete/', [ProductsController::class, 'destroy'])->name('productdelete');
    Route::get('client-search-for-modal/', [ClientController::class, 'SearchClientListForModal'])->name('clientSearchModal');

    // Client
  
    Route::get('client-list/', [ClientController::class, 'index'])->name('clientList');
    Route::get('client-search/', [ClientController::class, 'search'])->name('clientSearch');
    //Route::get('client-search', 'ClientController@search');
    Route::post('client-add/', [ClientController::class, 'store'])->name('clientAdd');
    Route::post('client-update/', [ClientController::class, 'update'])->name('clientUpdate');
    //Route::post('client-delete/', [ClientController::class, 'destroy'])->name('clientDelete');
    Route::get('client-status-update/{user_id}/{status}', [ClientController::class, 'updateStatus'])->name('clientStatusUpdate');
    Route::any('client-mail-login-detail/', [ClientController::class, 'SendLoginDetail'])->name('clientMailLoginDetail');

    // Staff

    Route::get('staff-list/', [StaffController::class, 'index'])->name('staffList');
    Route::get('staff-search/', [StaffController::class, 'search'])->name('staffSearch');
    Route::post('staff-add/', [StaffController::class, 'store'])->name('staffAdd');
    Route::post('staff-update/', [StaffController::class, 'update'])->name('staffUpdate');
    //Route::post('client-delete/', [StaffController::class, 'destroy'])->name('clientDelete');
    Route::get('staff-status-update/{user_id}/{status}', [StaffController::class, 'updateStatus'])->name('staffStatusUpdate');

    // DocumentsController

    Route::get('documents-view/{invoice_id}', [DocumentsController::class, 'index'])->name('documentsView');
    Route::get('documents-view-ajax/{invoice_id}', [DocumentsController::class, 'getDetailFromMindee'])->name('documentsViewAjax');
    Route::get('client/', [DocumentsController::class, 'client'])->name('client');
    Route::get('client_view/', [DocumentsController::class, 'client_view'])->name('client_view');

    
    Route::get('invoice-details/{status?}/{user_id?}', [DocumentsController::class, 'invoice_details'])->name('invoice_details');
    Route::get('invoice-search/', [DocumentsController::class, 'search'])->name('invoiceSearch');
    Route::get('invoice-search-by-status/', [DocumentsController::class, 'search_by_status'])->name('invoiceSearchByStatus');
    Route::any('invoice-add/', [DocumentsController::class, 'invoice_add'])->name('invoiceAdd');
    Route::any('invoice-update/', [DocumentsController::class, 'invoice_update'])->name('invoiceUpdate');

    // Simcard 

    Route::get('simcard-list/', [SimcardController::class, 'index'])->name('simcardlist');
    Route::post('simcard-add/', [SimcardController::class, 'store'])->name('simcardadd');
    Route::post('simcard-update/', [SimcardController::class, 'update'])->name('simcardupdate');
    Route::post('simcard-delete/', [SimcardController::class, 'destroy'])->name('simcarddelete');

    // Orders

    Route::get('order-list/', [OrdersController::class, 'index'])->name('orderlist');
    Route::post('update-order-status/', [OrdersController::class, 'updateOrderStatus'])->name('updateOStatus');
    Route::post('view-order-item-list/', [OrdersController::class, 'viewOrderItemList'])->name('VOIlist');

    // Orders Assing to Vendor

    Route::post('order-assign-vendor-list/', [OrdersController::class, 'viewOrderAssignVendorList'])->name('OAVlist');
    Route::post('order-update-assign-vendor-quantity/', [OrdersController::class, 'updateOrderAssignVendorQuantity'])->name('updateorderAVQ');
    Route::post('order-assign-to-vendor/', [OrdersController::class, 'orderAssignToVendor'])->name('orderATV');
    Route::post('delete-order-assign-to-vendor/', [OrdersController::class, 'deleteOrderAssignToVendor'])->name('deleteorderATV');

    Route::get('view-order-union-quotation/{order_id}', [OrdersController::class, 'viewOrderUnionQuotation'])->name('vieworderUQ');
    Route::get('view-order-vendor-quotation/{order_id}/{vendor_id}/{product_id}/{order_item_id}', [OrdersController::class, 'viewOrderVendorQuotation'])->name('vieworderVQ');
    Route::get('view-order-simcardvendor-quotation/{order_id}/{vendor_id}/{product_id}/{order_item_id}', [OrdersController::class, 'viewOrderSimcardVendorQuotation'])->name('vieworderSimVQ');

    Route::get('order-vendor-list/', [OrdersController::class, 'orderVendorList'])->name('orderVL');
    Route::get('order-simcard-vendor-list/', [OrdersController::class, 'orderSimcardVendorList'])->name('orderSVL');
    Route::post('order-delete/', [OrdersController::class, 'destroy'])->name('orderdelete');
    
    // log route

    Route::get('log-list/', [SpLogController::class, 'index'])->name('loglist');
    Route::post('log-delete/', [SpLogController::class, 'destroy'])->name('logdelete');

    // Union Membership

    Route::get('union-membership-list/', [UnionMembershipController::class, 'index'])->name('umlist');
    Route::post('union-membership-add/', [UnionMembershipController::class, 'store'])->name('umadd');
    Route::post('union-membership-update/', [UnionMembershipController::class, 'update'])->name('umupdate');
    Route::get('union-membership-status-update/{user_id}/{status}', [UnionMembershipController::class, 'updateStatus'])->name('umustatusupdate');
    Route::get('union-membership-import/', [UnionMembershipController::class, 'importUnionMembership'])->name('umimport');
    Route::post('union-membership-upload/', [UnionMembershipController::class, 'uploadUnionMembership'])->name('umupload');
    Route::post('union-membership-delete/', [UnionMembershipController::class, 'destroy'])->name('umdelete');

    Route::get('generate-pdf/', [PdfGenerateController::class, 'pdfview'])->name('generate-pdf');

    //Route::get('generate-pdf', 'PdfGenerateController@pdfview')->name('generate-pdf');

});

Route::middleware('auth')->prefix('union')->name('union.')->group(function(){

    Route::get('union-order-list/', [UnionOrdersController::class, 'index'])->name('unionOL');
    Route::get('union-order-history/', [UnionOrdersController::class, 'UnionOrderHistory'])->name('unionOH');
    Route::post('union-order-item-list/', [UnionOrdersController::class, 'viewOrderItemList'])->name('unionOIL');
    Route::get('create-union-order-view/', [UnionOrdersController::class, 'createUnionOrderView'])->name('createUOV');
    Route::post('create-union-order/', [UnionOrdersController::class, 'createUnionOrder'])->name('createUN');
    Route::get('view-order-union-quotation/{order_id}', [UnionOrdersController::class, 'viewOrderUnionQuotation'])->name('vieworderUQF');
    Route::post('check-stock-by-product-id', [UnionOrdersController::class, 'checkStockByProductId'])->name('checkSBPId');
    Route::post('upload-union-order-signed-document', [UnionOrdersController::class, 'uploadUnionOrderSignedDocument'])->name('uploadUOSD');
    Route::post('order-cancel/', [UnionOrdersController::class, 'destroy'])->name('orderdelete');
});


Route::post('union-login/', [WebHomeController::class, 'UnionLogin'])->name('unionlogin');


Route::post('verify-union-membership/', [WebHomeController::class, 'verifyUnionMembership'])->name('verifyUMS');
Route::any('create-union-membership-order-quotation/', [WebHomeController::class, 'createUnionMembershipOrderQuotation'])->name('createUMOQ');
Route::post('product-load-more/', [WebHomeController::class, 'productLoadMore'])->name('proLoadMore');
Route::any('union-logout/', [WebHomeController::class, 'UnionLogout'])->name('unionlogout');

// union name seacrh
Route::get('union-name-list-autocomplete-search', [WebHomeController::class, 'unionNameListAutocompleteSearch']);
Route::get('union-name-list-with-id-autocomplete-search', [WebHomeController::class, 'unionNameListWithIdAutocompleteSearch']);

// device seacrh
Route::get('device-list-autocomplete-search', [WebHomeController::class, 'deviceListAutocompleteSearch']);
Route::post('product-list-with-search/', [WebHomeController::class, 'getProductWithSeacrh']);
Route::post('reset-product-search/', [WebHomeController::class, 'resetProductSeacrh']);

Auth::routes(['register' => false]); // put true to enable register

Route::middleware('auth')->get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
Route::middleware('auth')->get('home/{status?}/{user_id?}', [DocumentsController::class, 'invoice_details'])->name('home');


Route::get("/log", function(){ 
    Log::channel('i_love_this_logging_thing')->debug("Action log debug test", ['my-string' => 'log me', "run"]);
    die('yes');
    return ["result" => true];
});