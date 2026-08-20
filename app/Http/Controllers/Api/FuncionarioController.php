<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FuncionarioRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class FuncionarioController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $funcionarios = $request->user()
            ->funcionarios()
            ->with('departamento')
            ->orderByDesc('id')
            ->paginate(5);

        return response()->json([
            'status' => true,
            'funcionarios' => $funcionarios,
        ], 200);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $funcionario = $request->user()
            ->funcionarios()
            ->with('departamento', 'user')
            ->findOrFail($id);

        return response()->json([
            'status' => true,
            'funcionario' => $funcionario,
        ], 200);
    }

    public function store(FuncionarioRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('funcionarios', 'public');
            }

            $funcionario = $request->user()->funcionarios()->create([
                'department_id' => $request->department_id,
                'nome' => $request->nome,
                'email' => $request->email,
                'salario' => $request->salario,
                'data_nascimento' => $request->data_nascimento,
                'image' => $imagePath,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'funcionario' => $funcionario,
            ], 201);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Erro ao criar funcionário: '.$e->getMessage(),
            ], 400);
        }
    }

    public function update(FuncionarioRequest $request, int $id): JsonResponse
    {
        $funcionario = $request->user()->funcionarios()->findOrFail($id);

        DB::beginTransaction();

        try {
            $data = [
                'department_id' => $request->department_id,
                'nome' => $request->nome,
                'email' => $request->email,
                'salario' => $request->salario,
                'data_nascimento' => $request->data_nascimento,
            ];

            if ($request->hasFile('image')) {
                if ($funcionario->image) {
                    Storage::disk('public')->delete($funcionario->image);
                }
                $data['image'] = $request->file('image')->store('funcionarios', 'public');
            }

            $funcionario->update($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'funcionario' => $funcionario,
                'message' => 'Funcionário atualizado com sucesso',
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Erro ao atualizar funcionário: '.$e->getMessage(),
            ], 400);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $funcionario = $request->user()->funcionarios()->findOrFail($id);

        DB::beginTransaction();

        try {
            $funcionario->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Funcionário movido para a lixeira',
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Erro ao deletar funcionário: '.$e->getMessage(),
            ], 400);
        }
    }

    // ---- LIXEIRA ----

    public function trashed(Request $request): JsonResponse
    {
        $funcionarios = $request->user()
            ->funcionarios()
            ->onlyTrashed()
            ->with('departamento')
            ->orderByDesc('deleted_at')
            ->paginate(5);

        return response()->json([
            'status' => true,
            'funcionarios' => $funcionarios,
        ], 200);
    }

    public function restore(Request $request, int $id): JsonResponse
    {
        $funcionario = $request->user()->funcionarios()->onlyTrashed()->findOrFail($id);
        $funcionario->restore();

        return response()->json([
            'status' => true,
            'message' => 'Funcionário restaurado com sucesso',
            'funcionario' => $funcionario,
        ], 200);
    }

    public function forceDelete(Request $request, int $id): JsonResponse
    {
        $funcionario = $request->user()->funcionarios()->onlyTrashed()->findOrFail($id);

        if ($funcionario->image) {
            Storage::disk('public')->delete($funcionario->image);
        }

        $funcionario->forceDelete();

        return response()->json([
            'status' => true,
            'message' => 'Funcionário eliminado definitivamente',
        ], 200);
    }
}
