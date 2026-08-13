<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DepartamentoController;

/*
|--------------------------------------------------------------------------
| Usuário autenticado
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/*
|--------------------------------------------------------------------------
| Rotas protegidas
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // =========================
    // USUÁRIOS
    // =========================

    // Lixeira
    Route::get('/users/trashed/list', [UserController::class, 'trashed']);
    Route::put('/users/{id}/restore', [UserController::class, 'restore']);
    Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete']);

    // CRUD
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);


    // =========================
    // DEPARTAMENTOS
    // =========================

    // Lixeira
    Route::get('/departamento/trashed/list', [DepartamentoController::class, 'trashed']);
    Route::put('/departamento/{id}/restore', [DepartamentoController::class, 'restore']);
    Route::delete('/departamento/{id}/force-delete', [DepartamentoController::class, 'forceDelete']);

    // CRUD
    Route::get('/departamento', [DepartamentoController::class, 'index']);
    Route::get('/departamento/{departamento}', [DepartamentoController::class, 'show']);
    Route::post('/departamento', [DepartamentoController::class, 'store']);
    Route::put('/departamento/{departamento}', [DepartamentoController::class, 'update']);
    Route::delete('/departamento/{departamento}', [DepartamentoController::class, 'destroy']);
});
