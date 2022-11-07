<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


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
Route::get('/test', function () {
    return "invoice APIs working";
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/auh_test', function () {
        return Auth::user();
    });
    
    Route::apiResource('/categories',CategoryController::class)->names('categories');
    Route::apiResource('/people',PeopleController::class)->names('people');
    Route::apiResource('/projects',ProjectController::class)->names('projects');
    Route::apiResource('/accounts',AccountsController::class)->names('accounts');
    Route::apiResource('/transactions',TransactionController::class)->names('transactions');

});



Route::post('/login', [AuthController::class, 'login'])->name('login');
