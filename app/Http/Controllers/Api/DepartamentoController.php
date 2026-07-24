<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function index(): JsonResponse
    {
        $departamentos = Departamento::orderByDesc('id')->paginate(5);

        return response()->json([
            'status' => 'true',
            'departamentos' => $departamentos,
        ], 200);
    }

    public function show(Departamento $departamento): JsonResponse
    {
        return response()->json([
            'status' => 'true',
            'departamento' => $departamento,
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $departamento = Departamento::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'true',
                'departamento' => $departamento,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'false',
                'message' => 'Erro ao criar departamento: '.$e->getMessage(),
            ], 400);
        }
    }

    public function update(Request $request, Departamento $departamento): JsonResponse
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $departamento->update([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'true',
                'departamento' => $departamento,
                'message' => 'Departamento atualizado com sucesso',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'false',
                'message' => 'Erro ao atualizar departamento: '.$e->getMessage(),
            ], 400);
        }
    }

    public function destroy(Departamento $departamento): JsonResponse
    {
        DB::beginTransaction();

        try {
            $departamento->delete();

            DB::commit();

            return response()->json([
                'status' => 'true',
                'message' => 'Departamento deletado com sucesso',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'false',
                'message' => 'Erro ao deletar departamento: '.$e->getMessage(),
            ], 400);
        }
    }

    public function trashed(): JsonResponse
    {
        $departamentos = Departamento::onlyTrashed()->orderByDesc('deleted_at')->paginate(5);

        return response()->json([
            'status' => 'true',
            'departamentos' => $departamentos,
        ], 200);
    }

    public function restore($id): JsonResponse
    {
        $departamento = Departamento::onlyTrashed()->findOrFail($id);
        $departamento->restore();

        return response()->json([
            'status' => 'true',
            'message' => 'Departamento restaurado com sucesso',
            'departamento' => $departamento,
        ], 200);
    }

    public function forceDelete($id): JsonResponse
    {
        $departamento = Departamento::onlyTrashed()->findOrFail($id);
        $departamento->forceDelete();

        return response()->json([
            'status' => 'true',
            'message' => 'Departamento eliminado definitivamente',
        ], 200);
    }
    }
