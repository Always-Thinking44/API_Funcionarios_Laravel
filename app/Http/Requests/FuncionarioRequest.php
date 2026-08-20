<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FuncionarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $funcionarioId = $this->route('id');

        return [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:funcionarios,email,'.$funcionarioId,
            'salario' => 'required|numeric|min:50000',
            'data_nascimento' => 'required|date|before_or_equal:-18 years',
            'department_id' => 'required|exists:departamentos,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}
