<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadJournalRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:xls,xlsx,csv',
                'max:10240',
            ],
            'import_name' => ['required', 'string', 'max:255'],
            'imported_at' => ['nullable', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Silakan pilih file jurnal untuk diunggah.',
            'file.mimes' => 'Format file harus XLS, XLSX, atau CSV.',
            'file.max' => 'Ukuran file maksimal 10MB.',
            'import_name.required' => 'Nama batch upload wajib diisi.',
        ];
    }
}
