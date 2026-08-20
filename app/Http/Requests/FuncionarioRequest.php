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
            'data_nascimento' => [
                'required',
                'date',
                function (string $attribute, mixed $value, Closure $fail) {
                    $age = now()->diffInYears(\Carbon\Carbon::parse($value));
                    if ($age < 18) {
                        $fail('O funcionário deve ter pelo menos 18 anos.');
                    }
                },
            ],
            'department_id' => 'required|exists:departamentos,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}
