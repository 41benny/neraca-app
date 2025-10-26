<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJournalLineRequest extends FormRequest
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
            'journal_date' => ['required', 'date'],
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
            'document_no' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'debit' => ['nullable', 'numeric', 'min:0'],
            'credit' => ['nullable', 'numeric', 'min:0'],
            'branch_code' => ['nullable', 'string', 'max:50'],
            'invoice_id' => ['nullable', 'string', 'max:100'],
            'project_id' => ['nullable', 'string', 'max:100'],
            'vehicle_id' => ['nullable', 'string', 'max:100'],
            'party_type' => ['nullable', 'string', 'max:20'],
            'party_code' => ['nullable', 'string', 'max:100'],
            'party_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
