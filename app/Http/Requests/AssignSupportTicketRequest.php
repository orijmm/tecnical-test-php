<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AssignSupportTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return [
            'agent_id' => [
                'required',
                'integer',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (! User::agents()->whereKey($value)->exists()) {
                        $fail('El usuario seleccionado no es un agente.');
                    }
                },
            ],
        ];
    }
}
