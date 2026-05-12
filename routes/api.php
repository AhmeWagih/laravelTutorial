<?php

use App\Http\Controllers\Api\AuthTokenController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthTokenController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthTokenController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });

    Route::apiResource('tasks', TaskController::class)
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names([
            'index' => 'api.tasks.index',
            'show' => 'api.tasks.show',
            'store' => 'api.tasks.store',
            'update' => 'api.tasks.update',
            'destroy' => 'api.tasks.destroy',
        ]);
});
