<?php

namespace App\Http\Controllers\Api;
use App\Http\Requests\UserRequest;
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
        $users = User::orderByDesc('id')->paginate(5);

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

    public function store(UserRequest $request) : JsonResponse
    {

        //Inicia uma transação no banco de dados
        DB::beginTransaction();

        try {

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


    public function update(UserRequest $request, User $user) : JsonResponse
    {
        DB::beginTransaction();

        try {
            //Atualiza o usuário com os dados validados
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            DB::commit();

            return response()->json([
                'status' => 'true',
                'user' => $user,
                'message' => 'Usuário atualizado com sucesso',
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'false',
                'message' => 'Erro ao atualizar usuário: '.$e->getMessage(),
            ], 400);
        }

        return response()->json([
            'status' => 'true',
            'user' => $user,
            'message' => 'Usuário atualizado com sucesso',
        ], 200);

    }

    public function destroy(User $user) : JsonResponse
    {
        DB::beginTransaction();

        try {
            //Deleta o usuário do banco de dados
            $user->delete();

            DB::commit();

            return response()->json([
                'status' => 'true',
                'message' => 'Usuário deletado com sucesso',
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'false',
                'message' => 'Erro ao deletar usuário: '.$e->getMessage(),
            ], 400);
        }
    }

}
