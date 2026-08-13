<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::orderByDesc('id')->paginate(5);

        return response()->json([
            'status' => true,
            'users' => $users,
        ], 200);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'status' => true,
            'user' => $user,
        ], 200);
    }

    public function store(UserRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'user' => $user,
            ], 201);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Erro ao criar usuário: '.$e->getMessage(),
            ], 400);
        }
    }

    public function update(UserRequest $request, User $user): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }

            $user->update($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'Usuário atualizado com sucesso',
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Erro ao atualizar usuário: '.$e->getMessage(),
            ], 400);
        }
    }

    public function destroy(User $user): JsonResponse
    {
        DB::beginTransaction();

        try {
            $user->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Usuário movido para a lixeira',
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Erro ao deletar usuário: '.$e->getMessage(),
            ], 400);
        }
    }

    // ---- LIXEIRA ----

    public function trashed(): JsonResponse
    {
        $users = User::onlyTrashed()->orderByDesc('deleted_at')->paginate(5);

        return response()->json([
            'status' => true,
            'users' => $users,
        ], 200);
    }

    public function restore(int $id): JsonResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return response()->json([
            'status' => true,
            'message' => 'Usuário restaurado com sucesso',
            'user' => $user,
        ], 200);
    }

    public function forceDelete(int $id): JsonResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return response()->json([
            'status' => true,
            'message' => 'Usuário eliminado definitivamente',
        ], 200);
    }
}
