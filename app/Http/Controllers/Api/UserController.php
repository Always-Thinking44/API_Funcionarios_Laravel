<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::with('departamento')->orderByDesc('id')->paginate(5);

        return response()->json([
            'status' => 'true',
            'users' => $users,
        ], 200);
    }

    public function show(User $user): JsonResponse
    {
        $user->load('departamento');

        return response()->json([
            'status' => 'true',
            'user' => $user,
        ], 200);
    }

    public function store(UserRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('users', 'public');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'department_id' => $request->department_id,
                'image' => $imagePath,
            ]);

            DB::commit();

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

    public function update(UserRequest $request, User $user): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'department_id' => $request->department_id,
            ];

            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }

            if ($request->hasFile('image')) {
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }
                $data['image'] = $request->file('image')->store('users', 'public');
            }

            $user->update($data);

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
    }

    public function destroy(User $user): JsonResponse
    {
        DB::beginTransaction();

        try {
            $user->delete(); // soft delete, não apaga de facto

            DB::commit();

            return response()->json([
                'status' => 'true',
                'message' => 'Usuário movido para a lixeira',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'false',
                'message' => 'Erro ao deletar usuário: '.$e->getMessage(),
            ], 400);
        }
    }

    // ---- LIXEIRA ----

    public function trashed(): JsonResponse
    {
        $users = User::onlyTrashed()->with('departamento')->orderByDesc('deleted_at')->paginate(5);

        return response()->json([
            'status' => 'true',
            'users' => $users,
        ], 200);
    }

    public function restore($id): JsonResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return response()->json([
            'status' => 'true',
            'message' => 'Usuário restaurado com sucesso',
            'user' => $user,
        ], 200);
    }

    public function forceDelete($id): JsonResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);

        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        $user->forceDelete();

        return response()->json([
            'status' => 'true',
            'message' => 'Usuário eliminado definitivamente',
        ], 200);
    }
}
