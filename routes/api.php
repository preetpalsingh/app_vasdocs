<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api'], function ($router) {

    $router->post('login', [AuthController::class, 'login']);
    $router->post('/forgot-password/send-otp', [AuthController::class, 'SendResetPasswordOtp']);
    $router->post('/forgot-password/verify-otp', [AuthController::class, 'verifyOtpAndReset']);
    $router->post('/forgot-password/reset-password', [AuthController::class, 'ResetPassword']);

    $router->any('get-client-document-list-test/{last_id?}', [AuthController::class, 'GetClientDocumentListTest'])->name('GetClientDocumentListTest');
    $router->any('generate-pdf', [AuthController::class, 'generatePdf'])->name('generatePdf');
 
});

Route::middleware(['token.validate', 'auth:api'])->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('upload_document', [AuthController::class, 'upload_document']);
    //Route::post('save_upload_document', [AuthController::class, 'save_upload_document']);
    
    Route::any('get-client-document-list/', [AuthController::class, 'getClientDocumentList'])->name('GetClientDocumentList');
    Route::any('get-client-list/{last_id?}', [AuthController::class, 'getClientList'])->name('GetClientList');
    // Other protected routes...
});

//Route::post('login', [AuthController::class, 'login']);
//Route::middleware('auth:api')->get('user', [AuthController::class, 'user']);
