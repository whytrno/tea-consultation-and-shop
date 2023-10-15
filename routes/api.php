<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ExpertController;
use App\Http\Controllers\HarvestController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PestController;
use App\Http\Controllers\PesticideController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SavedController;
use App\Http\Controllers\TeaController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('products', ProductController::class)->except(['update']);
    Route::post('products/{id}/update', [ProductController::class, 'update']);
    Route::resource('categories', CategoryController::class);
    Route::resource('carts', CartController::class)->except(['show']);
    Route::resource('saved', SavedController::class)->except(['show', 'update']);
    Route::resource('users', UserController::class);
    Route::resource('pests', PestController::class)->except(['update']);
    Route::post('pests/{id}/update', [PestController::class, 'update']);
    Route::resource('pesticides', PesticideController::class)->except(['update']);
    Route::post('pesticides/{id}/update', [PesticideController::class, 'update']);
    Route::resource('experts', ExpertController::class)->except(['update']);
    Route::post('experts/{id}/update', [ExpertController::class, 'update']);
    Route::resource('chats', ChatController::class)->except(['update']);
    Route::resource('payments', PaymentController::class);
    Route::resource('messages', MessageController::class)->except(['show', 'update', 'destroy']);
    Route::resource('transactions', TransactionController::class)->except(['destroy']);
    Route::put('transactions/{id}/update-status', [TransactionController::class, 'updateStatus']);
    Route::post('transactions/{id}/update', [TransactionController::class, 'update']);
    Route::get('statistic', [TransactionController::class, 'statistic']);
    Route::get('all-statistic', [TransactionController::class, 'allStatistic']);
    Route::get('statistic-by-year/{year}', [TransactionController::class, 'statisticByYear']);

    Route::resource('tea', TeaController::class)->except(['update']);
    Route::post('tea/{id}/update', [TeaController::class, 'update']);
    Route::resource('harvest', HarvestController::class)->except(['update']);
    Route::post('harvest/{id}/update', [HarvestController::class, 'update']);

    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('change-password', [AuthController::class, 'changePassword']);
    Route::get('logout', [AuthController::class, 'logout']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);