<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArApSummaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['nullable', 'in:ar,ap'],
            'account_id' => ['nullable', 'integer', 'exists:accounts,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'party' => ['nullable', 'string', 'max:255'],
            'invoice_id' => ['nullable', 'string', 'max:100'],
        ];
    }
}
