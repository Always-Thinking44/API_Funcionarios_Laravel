<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FuncionarioUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $funcionarioId = $this->route('id');

        return [
            'nome' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:funcionarios,email,'.$funcionarioId,
            'salario' => 'sometimes|numeric|min:50000',
            'data_nascimento' => 'sometimes|date|before_or_equal:-18 years',
            'department_id' => 'sometimes|exists:departamentos,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}
