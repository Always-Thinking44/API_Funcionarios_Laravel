<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Http\Controllers\Controller;
use DB;
use Exception;
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

    public function store(Request $request) : JsonResponse
    {

        //Inicia uma transação no banco de dados
        DB::beginTransaction();

        try {
            //Valida os dados recebidos na requisição
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            //Cria um novo usuário com os dados validados
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            //Salva o usuário no banco de dados
            DB::commit();

            //Retorna o usuário criado em formato JSON
            return response()->json([
                'status' => 'true',
                'user' => $user,
            ], 201);
        } catch (Exception $e) {

            DB::rollBack();
            return response()->json([
                'status' => 'false',
                'message' => 'Erro ao criar usuário: '.$e->getMessage(),
            ], 400);
        }

    }

}
