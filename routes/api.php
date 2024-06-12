<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/user', fn (Request $request) => UserResource::make($request->user()));

    Route::apiResources([
        'account' => AccountsController::class,
        'project' => ProjectsController::class,
        'transaction' => TransactionsController::class,
    ]);

    Route::apiResource('bank', BankController::class)->only('index', 'show');

    Route::group(['prefix' => 'project'], function () {
        Route::get('/{id}/transactions', [ProjectsController::class, 'transactions']);
        Route::get('/{id}/accounts', [ProjectsController::class, 'accounts']);
    });

    Route::group(['prefix' => 'transaction/import'], function () {
        Route::post('/ofx', [ImportController::class, 'importOfx']);
        Route::post('/csv', [ImportController::class, 'importCsv']);
    });

    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/total-balance', [DashboardController::class, 'totalBalance']);
        Route::get('/monthly-income', [DashboardController::class, 'monthlyIncome']);
        Route::get('/monthly-expense', [DashboardController::class, 'monthlyExpense']);
    });
});
