<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreSettlementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from_bank_id' => ['required', 'exists:banks,id'],
            'to_bank_id' => ['required', 'exists:banks,id', 'different:from_bank_id'],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'utr' => ['nullable', 'string', 'max:255'],
            'remark' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'to_bank_id.different' => 'Source and destination banks must be different.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'created_by' => auth()->id(),
        ]);
    }
}
