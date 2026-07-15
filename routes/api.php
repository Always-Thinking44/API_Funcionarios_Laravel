<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;


Route::get('/user', [UserController::class, 'index']); // GET - 127.0.0.1:8000/api/user?page=1
Route::get('/user/{user}', [UserController::class, 'show']); // GET - 127.0.0.1:8000/api/user/1
Route::post('/user', [UserController::class, 'store']); // POST - 127.0.0.1:8000/api/user
Route::put('/user/{user}', [UserController::class, 'update']); // PUT - 127.0.0.1:8000/api/user/1
Route::delete('/user/{user}', [UserController::class, 'destroy']); // DELETE - 127.0.0.1:8000/api/user/1
