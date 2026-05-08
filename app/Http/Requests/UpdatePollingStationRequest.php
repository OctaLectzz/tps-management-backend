<?php

namespace App\Http\Requests;

use App\Enums\PollingStationStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePollingStationRequest extends FormRequest
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
            'village_id' => 'sometimes|required|string|exists:villages,id',
            'district_id' => 'sometimes|required|string|exists:districts,id',
            'station_number' => [
                'sometimes',
                'required',
                'integer',
                'min:1',
                Rule::unique('polling_stations')->where(function ($query) {
                    return $query->where('village_id', $this->village_id ?? $this->route('polling_station')->village_id);
                })->ignore($this->route('polling_station')),
            ],
            'venue_name' => 'sometimes|required|string|max:200',
            'address' => 'sometimes|required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => ['sometimes', Rule::enum(PollingStationStatus::class)],
            'notes' => 'nullable|string',
        ];
    }
}
