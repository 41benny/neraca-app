<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCashTransactionRequest extends FormRequest
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
            'type' => ['required', Rule::in(['in', 'out'])],
            'journal_date' => ['required', 'date'],
            'cash_account_id' => [
                'nullable',
                'integer',
                Rule::exists('accounts', 'id')->where(fn ($q) => $q->where('is_cash_account', true)),
            ],
            // Single offset mode
            'offset_account_id' => [
                'nullable',
                'integer',
                Rule::exists('accounts', 'id'),
                'different:cash_account_id',
            ],
            'amount' => ['nullable', 'numeric', 'min:0.01'],

            // Split mode (optional)
            'lines' => ['nullable', 'array'],
            'lines.*.offset_account_id' => [
                'nullable',
                'integer',
                Rule::exists('accounts', 'id'),
            ],
            'lines.*.amount' => ['nullable', 'numeric', 'min:0.01'],

            'document_no' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'branch_code' => ['nullable', 'string', 'max:50'],

            // Party info (optional)
            'party_type' => ['nullable', Rule::in(['customer', 'supplier', 'other'])],
            'party_code' => ['nullable', 'string', 'max:100'],
            'party_name' => ['nullable', 'string', 'max:255'],

            // Attachment (optional)
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:20480'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Pilih jenis transaksi (masuk/keluar).',
            'journal_date.required' => 'Tanggal transaksi wajib diisi.',
            'cash_account_id.required' => 'Akun kas/bank wajib dipilih.',
            'cash_account_id.exists' => 'Akun kas/bank tidak valid.',
            'offset_account_id.exists' => 'Akun lawan tidak valid.',
            'offset_account_id.different' => 'Akun kas/bank tidak boleh sama dengan akun lawan.',
            'amount.min' => 'Nominal harus lebih besar dari 0.',
            'lines.array' => 'Format split akun lawan tidak valid.',
            'lines.*.offset_account_id.exists' => 'Akun lawan pada baris split tidak valid.',
            'lines.*.amount.min' => 'Nominal pada baris split harus lebih besar dari 0.',
            'attachment.mimes' => 'Lampiran harus PDF/JPG/PNG/WebP.',
            'attachment.max' => 'Lampiran maksimal 20MB.',
        ];
    }

    protected function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $lines = collect($this->input('lines', []))
                ->filter(fn ($l) => ($l['offset_account_id'] ?? null) && (float) ($l['amount'] ?? 0) > 0);

            $singleAmount = (float) $this->input('amount', 0);
            $singleOffset = $this->input('offset_account_id');

            if ($lines->isEmpty() && (! $singleOffset || $singleAmount <= 0)) {
                $validator->errors()->add('amount', 'Isi nominal dan akun lawan atau gunakan split.');
            }

            // Ensure split lines differ from cash account
            $cashId = (int) $this->input('cash_account_id');
            if ($cashId) {
                foreach ($lines as $idx => $l) {
                    if ((int) $l['offset_account_id'] === $cashId) {
                        $validator->errors()->add("lines.$idx.offset_account_id", 'Akun lawan tidak boleh sama dengan akun kas/bank.');
                    }
                }
            }
        });
    }
}
