<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DepartamentoController;

// lixeira — colocadas antes de /user/{user} por segurança/clareza
Route::get('/user/trashed/list', [UserController::class, 'trashed']);
Route::put('/user/{id}/restore', [UserController::class, 'restore']);
Route::delete('/user/{id}/force-delete', [UserController::class, 'forceDelete']);


Route::get('/user', [UserController::class, 'index']); // GET - 127.0.0.1:8000/api/user?page=1
Route::get('/user/{user}', [UserController::class, 'show']); // GET - 127.0.0.1:8000/api/user/1
Route::post('/user', [UserController::class, 'store']); // POST - 127.0.0.1:8000/api/user
Route::put('/user/{user}', [UserController::class, 'update']); // PUT - 127.0.0.1:8000/api/user/1
Route::delete('/user/{user}', [UserController::class, 'destroy']); // DELETE - 127.0.0.1:8000/api/user/1



Route::get('/departamento/trashed/list', [DepartamentoController::class, 'trashed']);
Route::put('/departamento/{id}/restore', [DepartamentoController::class, 'restore']);
Route::delete('/departamento/{id}/force-delete', [DepartamentoController::class, 'forceDelete']);

Route::get('/departamento', [DepartamentoController::class, 'index']);
Route::get('/departamento/{departamento}', [DepartamentoController::class, 'show']);
Route::post('/departamento', [DepartamentoController::class, 'store']);
Route::put('/departamento/{departamento}', [DepartamentoController::class, 'update']);
Route::delete('/departamento/{departamento}', [DepartamentoController::class, 'destroy']);
