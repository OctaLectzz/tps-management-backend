<?php

namespace App\Http\Requests;

use App\Enums\ConfirmationStatus;
use App\Enums\OfficerRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssignmentRequest extends FormRequest
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
            'polling_station_id' => 'sometimes|required|integer|exists:polling_stations,id',
            'officer_id' => 'sometimes|required|integer|exists:officers,id',
            'role' => ['sometimes', 'required', Rule::enum(OfficerRole::class)],
            'confirmation_status' => ['sometimes', Rule::enum(ConfirmationStatus::class)],
            'notes' => 'nullable|string',
            'assigned_at' => 'sometimes|required|date',
        ];
    }
}
