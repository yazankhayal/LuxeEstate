<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:100'],
            'email'       => ['required', 'email', 'max:150'],
            'phone'       => ['nullable', 'string', 'max:30'],
            'subject'     => ['nullable', 'string', 'max:255'],
            'message'     => ['required', 'string', 'min:10', 'max:2000'],
            'property_id' => ['nullable', 'exists:properties,id'],
        ];
    }
}
