<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DepartamentoController;
use App\Http\Controllers\Api\FuncionarioController;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Usuário autenticado
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password,
    ]);

    return response()->json([
        'user' => $user,
        'token' => $user->createToken('insomnia')->plainTextToken
    ], 201);
});

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }

    return response()->json([
        'token' => $user->createToken('insomnia')->plainTextToken
    ]);
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
    // FUNCIONÁRIOS
    // =========================

    // Lixeira
    Route::get('/funcionarios/trashed/list', [FuncionarioController::class, 'trashed']);
    Route::put('/funcionarios/{id}/restore', [FuncionarioController::class, 'restore']);
    Route::delete('/funcionarios/{id}/force-delete', [FuncionarioController::class, 'forceDelete']);

    // CRUD
    Route::get('/funcionarios', [FuncionarioController::class, 'index']);
    Route::get('/funcionarios/{id}', [FuncionarioController::class, 'show']);
    Route::post('/funcionarios', [FuncionarioController::class, 'store']);
    Route::put('/funcionarios/{id}', [FuncionarioController::class, 'update']);
    Route::delete('/funcionarios/{id}', [FuncionarioController::class, 'destroy']);


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
