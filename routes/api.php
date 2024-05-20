<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/user', fn (Request $request) => UserResource::make($request->user()));
    Route::get('/projects', [ProjectsController::class, 'index'])->name('projects.index');
    Route::get('/accounts', [AccountsController::class, 'index'])->name('accounts.index');
});
