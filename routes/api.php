<?php

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return UserResource::make($request->user()->load('projects'));
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/projects', 'App\Http\Controllers\ProjectsController@userProjects');
});
