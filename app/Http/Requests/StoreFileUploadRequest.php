<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFileUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:xlsx,csv|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'File is required.',
            'file.mimes' => 'Only PDF, Word, Excel, CSV, and text files are allowed.',
            'file.max' => 'The file must not be greater than 10MB.',
        ];
    }
}
