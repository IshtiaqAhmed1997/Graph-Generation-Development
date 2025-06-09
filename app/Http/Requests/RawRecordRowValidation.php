<?php

namespace App\Http\Requests;

class RawRecordRowValidation
{
    public static function rules(): array
    {
        return [
            'client_name' => 'required|string|max:255',
            'provider_name' => 'required|string|max:255',
            'date_of_service' => 'required|date',
            'program_name' => 'nullable|string|max:255',
            'target_text' => 'required|string|max:255',
            'raw_data' => 'nullable|string',
            'symbolic_data' => 'nullable|string',
            'accuracy' => 'nullable|numeric|min:0|max:100',
            'cpt_code' => 'nullable|string|max:50',
        ];
    }
}
