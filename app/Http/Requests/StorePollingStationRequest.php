<?php

namespace App\Http\Requests;

use App\Enums\PollingStationStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePollingStationRequest extends FormRequest
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
            'village_id' => 'required|exists:villages,id',
            'district_id' => 'required|exists:districts,id',
            'station_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('polling_stations')->where(function ($query) {
                    return $query->where('village_id', $this->village_id);
                }),
            ],
            'venue_name' => 'required|string|max:200',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => ['nullable', Rule::enum(PollingStationStatus::class)],
            'notes' => 'nullable|string',
        ];
    }
}
