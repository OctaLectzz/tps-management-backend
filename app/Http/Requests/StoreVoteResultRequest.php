<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVoteResultRequest extends FormRequest
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
            'polling_station_id' => [
                'required',
                'integer',
                'exists:polling_stations,id',
                Rule::unique('vote_results', 'polling_station_id'),
            ],
            'party_votes' => 'required|integer|min:0',
            'total_votes' => 'required|integer|min:0',
            'dpt' => 'required|integer|min:0',
            'voters_present' => 'required|integer|min:0',
        ];
    }
}
