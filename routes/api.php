<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;


Route::get('/user', [UserController::class, 'index']); // GET - 127.0.0.1:8000/api/user?page=1
Route::get('/user/{user}', [UserController::class, 'show']); // GET - 127.0.0.1:8000/api/user/1
