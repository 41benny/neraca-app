<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncomeStatementRequest extends FormRequest
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
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'branch_code' => ['nullable', 'string', 'max:50'],
            'invoice_id' => ['nullable', 'string', 'max:100'],
            'project_id' => ['nullable', 'string', 'max:100'],
            'vehicle_id' => ['nullable', 'string', 'max:100'],
        ];
    }
}
