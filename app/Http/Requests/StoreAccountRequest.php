<?php

namespace App\Http\Requests;

use App\Enums\AccountNormalBalance;
use App\Enums\ReportType;
use App\Enums\StatementSide;
use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:50', 'unique:accounts,code'],
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:accounts,id'],
            'level' => ['nullable', 'integer', 'min:1', 'max:9'],
            'normal_balance' => ['required', 'in:'.implode(',', array_map(fn ($e) => $e->value, AccountNormalBalance::cases()))],
            'account_type' => ['required', 'string', 'max:50'],
            'is_cash_account' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string', 'max:255'],

            // Mapping (opsional)
            'report_type' => ['nullable', 'in:'.implode(',', array_map(fn ($e) => $e->value, ReportType::cases()))],
            'group_name' => ['nullable', 'string', 'max:255'],
            'side' => ['nullable', 'in:'.implode(',', array_map(fn ($e) => $e->value, StatementSide::cases()))],
            'sign' => ['nullable', 'integer', 'in:-1,1'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:9999'],

            // Opening balance (opsional)
            'opening_debit' => ['nullable', 'numeric'],
            'opening_credit' => ['nullable', 'numeric'],
            'opening_as_of' => ['nullable', 'date'],
            'branch_code' => ['nullable', 'string', 'max:50'],
        ];
    }
}
