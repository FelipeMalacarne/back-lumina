<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/user', fn (Request $request) => UserResource::make($request->user()));
    Route::get('/account', [AccountsController::class, 'index'])->name('accounts.index');

    Route::group(['prefix' => 'project'], function () {
        Route::get('/', [ProjectsController::class, 'index']);
        Route::post('/', [ProjectsController::class, 'store']);
        Route::get('/{id}/transactions', [ProjectsController::class, 'transactions']);
        Route::get('/{id}/accounts', [ProjectsController::class, 'accounts']);
        Route::get('/{project}', [ProjectsController::class, 'show']);
        Route::put('/{project}', [ProjectsController::class, 'update']);
        Route::delete('/{project}', [ProjectsController::class, 'destroy']);
    });

    Route::group(['prefix' => 'transaction'], function () {
        Route::get('/', [TransactionsController::class, 'index']);
        Route::post('/', [TransactionsController::class, 'store']);
        Route::get('/{transaction}', [TransactionsController::class, 'show']);
        Route::put('/{transaction}', [TransactionsController::class, 'update']);
        Route::delete('/{transaction}', [TransactionsController::class, 'destroy']);

        Route::post('/ofx', [ProjectsController::class, 'importOfx']);
        Route::post('/csv', [ProjectsController::class, 'importCsv']);
    });
});
