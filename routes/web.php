<?php

use App\Http\Controllers\WebHomeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SpLogController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\DeveloperController;
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


//Route::middleware('auth')->prefix('banner')->name('banner.')->group(function(){
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function(){

    // Client
  
    Route::get('client-list/', [ClientController::class, 'index'])->name('clientList');
    Route::get('client-search/', [ClientController::class, 'search'])->name('clientSearch');
    //Route::get('client-search', 'ClientController@search');
    Route::post('client-add/', [ClientController::class, 'store'])->name('clientAdd');
    Route::post('client-update/', [ClientController::class, 'update'])->name('clientUpdate');
    //Route::post('client-delete/', [ClientController::class, 'destroy'])->name('clientDelete');
    Route::get('client-status-update/{user_id}/{status}', [ClientController::class, 'updateStatus'])->name('clientStatusUpdate');
    Route::any('client-mail-login-detail/', [ClientController::class, 'SendLoginDetail'])->name('clientMailLoginDetail');
    Route::get('client-search-for-modal/', [ClientController::class, 'SearchClientListForModal'])->name('clientSearchModal');

    // Staff

    Route::get('staff-list/', [StaffController::class, 'index'])->name('staffList');
    Route::get('staff-search/', [StaffController::class, 'search'])->name('staffSearch');
    Route::post('staff-add/', [StaffController::class, 'store'])->name('staffAdd');
    Route::post('staff-update/', [StaffController::class, 'update'])->name('staffUpdate');
    //Route::post('client-delete/', [StaffController::class, 'destroy'])->name('clientDelete');
    Route::get('staff-status-update/{user_id}/{status}', [StaffController::class, 'updateStatus'])->name('staffStatusUpdate');

    // DocumentsController

    Route::get('documents-view/{invoice_id}', [DocumentsController::class, 'index'])->name('documentsView');
    Route::get('documents-download/{invoice_id}', [DocumentsController::class, 'download'])->name('documentsDownload');
    Route::get('documents-view-ajax/{invoice_id}', [DocumentsController::class, 'getDetailFromMindee'])->name('documentsViewAjax');
    Route::get('client/{invoice_id}', [DocumentsController::class, 'client'])->name('client');
    Route::get('client_view/', [DocumentsController::class, 'client_view'])->name('client_view');

    
    Route::get('invoice-details/{status?}/{user_id?}', [DocumentsController::class, 'invoice_details'])->name('invoice_details');
    Route::get('invoice-search/', [DocumentsController::class, 'search'])->name('invoiceSearch');
    Route::get('invoice-search-by-status/', [DocumentsController::class, 'search_by_status'])->name('invoiceSearchByStatus');
    Route::any('invoice-add/', [DocumentsController::class, 'invoice_add'])->name('invoiceAdd');
    Route::any('invoice-update/', [DocumentsController::class, 'invoice_update'])->name('invoiceUpdate');
    Route::any('invoice-update-status/', [DocumentsController::class, 'update_status'])->name('invoiceUpdateStatus');
    Route::post('invoice-export/', [DocumentsController::class, 'export'])->name('invoiceExport');
    Route::post('invoice-delete/', [DocumentsController::class, 'destroy'])->name('invoicedelete');

    // Developer

    Route::get('developer-list/', [DeveloperController::class, 'index'])->name('developerList');
    Route::get('developer-search/', [DeveloperController::class, 'search'])->name('developerSearch');
    Route::post('developer-add/', [DeveloperController::class, 'store'])->name('developerAdd');
    Route::post('developer-update/', [DeveloperController::class, 'update'])->name('developerUpdate');
    Route::post('developer-delete/', [DeveloperController::class, 'destroy'])->name('developerdelete');

    // log route

    Route::get('log-list/', [SpLogController::class, 'index'])->name('loglist');
    Route::post('log-delete/', [SpLogController::class, 'destroy'])->name('logdelete');

});

Auth::routes(['register' => false]); // put true to enable register

Route::middleware('auth')->get('/home', [HomeController::class, 'index'])->name('dashboard');
Route::middleware('auth')->get('invoice-details/{status?}/{user_id?}', [DocumentsController::class, 'invoice_details'])->name('home');


Route::get("/log", function(){ 
    Log::channel('i_love_this_logging_thing')->debug("Action log debug test", ['my-string' => 'log me', "run"]);
    die('yes');
    return ["result" => true];
});