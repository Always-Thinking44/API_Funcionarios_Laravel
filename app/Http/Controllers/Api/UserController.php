<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() : JsonResponse
    {
        $users = User::orderByDesc('id')->paginate(2);

        //Retorna os usuários paginados em formato JSON
        return response()->json([
            'status' => 'true',
            'users' => $users,
        ], 200);
    }

    public function show(User $user) : JsonResponse
    {
        //Retorna o usuário em formato JSON
        return response()->json([
            'status' => 'true',
            'user' => $user,
        ], 200);
    }

}
