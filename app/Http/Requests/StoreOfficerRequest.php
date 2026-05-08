<?php

namespace App\Http\Requests;

use App\Enums\OfficerRole;
use App\Enums\OfficerStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOfficerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'role' => ['required', Rule::enum(OfficerRole::class)],
            'district_id' => 'nullable|integer|exists:districts,id',
            'status' => ['nullable', Rule::enum(OfficerStatus::class)],
        ];
    }
}
