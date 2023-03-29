<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\CompanyPackageController;
use App\Http\Controllers\Api\CompanyPaymentController;

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
Route::post('/company/register', [CompanyController::class, 'createCompany']);
Route::post('/company/login', [CompanyController::class, 'loginCompany']);
Route::post('/company/check', [CompanyController::class, 'check']);

Route::post('/package/save', [PackageController::class, 'packageCreate']);

Route::post('/package/status-update', [PackageController::class, 'packageStatusUpdate']);
Route::post('/package/period-update', [PackageController::class, 'packagePeriodUpdate']);

Route::post('/companyPackage/assign', [CompanyPackageController::class, 'assign']);

Route::post('/company-payment', [CompanyPaymentController::class, 'create']);


