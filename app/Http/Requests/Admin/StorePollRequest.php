<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StorePollRequest extends FormRequest
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
            'question' => ['required', 'string', 'min:5', 'max:255'],
            'options' => ['required', 'array', 'min:2'],
            'options.*.text' => ['required', 'string', 'min:1', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $options = collect($this->input('options', []))
                ->pluck('text')
                ->filter()
                ->map(fn($v) => mb_strtolower(trim($v)));  // case-insensitive compare

            $duplicates = $options->duplicates();

            if ($duplicates->isNotEmpty()) {
                $validator->errors()->add(
                    'options',
                    'Each option must be unique. Duplicate found: "' . $duplicates->first() . '".'
                );
            }
        });
    }
}
