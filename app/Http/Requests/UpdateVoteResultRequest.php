<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVoteResultRequest extends FormRequest
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
            'party_votes' => 'sometimes|required|integer|min:0',
            'total_votes' => 'sometimes|required|integer|min:0',
            'dpt' => 'sometimes|required|integer|min:0',
            'voters_present' => 'sometimes|required|integer|min:0',
            'verified' => 'sometimes|boolean',
        ];
    }
}
