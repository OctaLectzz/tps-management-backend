<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$userId,
            'password' => $this->isMethod('post') ? 'required|string|min:8' : 'nullable|string|min:8',

            // Biodata
            'phone_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:M,F',

            'photo' => 'nullable|string',
            'role' => 'nullable|in:admin,operator',
            'status' => 'nullable|boolean',
        ];
    }
}
