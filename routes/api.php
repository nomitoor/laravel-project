<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\APIController;
use App\Http\Controllers\API\PackagesController;
use App\Http\Controllers\API\CustomerPackagesController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [APIController::class, 'register']);
Route::post('/login', [APIController::class, 'login']);

Route::group(['middleware' => 'jwt.verify', 'prefix' => 'v1'], function () {

    Route::post('/verify_code', [APIController::class, 'verify_code']);
    Route::post('/check_varification', [APIController::class, 'check_varification']);
    Route::post('/forget_password', [APIController::class, 'forget_password']);
    Route::post('/reset_password', [APIController::class, 'reset_password']);
    Route::post('/current_user', [APIController::class, 'userProfile']);
    Route::post('/refresh_token', [APIController::class, 'refresh']);
    Route::post('/logout', [APIController::class, 'logout']);

    Route::apiResource('/packages', PackagesController::class);
    Route::apiResource('/customer_packages', CustomerPackagesController::class);

    Route::put('/get_customer_details/{customer_id}', [CustomerPackagesController::class, 'get_customer_details']);
});
