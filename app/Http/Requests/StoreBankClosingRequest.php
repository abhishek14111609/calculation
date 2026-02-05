<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBankClosingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bank_id' => ['required', 'exists:banks,id'],
            'date' => ['required', 'date', 'unique:bank_closings,date,NULL,id,bank_id,' . $this->bank_id],
            'actual_closing' => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'date.unique' => 'A closing record already exists for this bank on this date.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'created_by' => auth()->id(),
        ]);
    }
}
