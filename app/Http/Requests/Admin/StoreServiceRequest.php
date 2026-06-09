<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $serviceId = $this->route('service')?->id;

        return [
            'slug'                        => ['required', 'string', Rule::unique('services', 'slug')->ignore($serviceId)],
            'icon'                        => ['required', 'string', 'max:50'],
            'order'                       => ['integer', 'min:0'],
            'is_active'                   => ['boolean'],
            'image'                       => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'translations'                => ['required', 'array'],
            'translations.en.title'       => ['required', 'string', 'max:255'],
            'translations.en.description' => ['nullable', 'string', 'max:500'],
            'translations.en.content'     => ['nullable', 'string'],
            'translations.ar.title'       => ['nullable', 'string', 'max:255'],
            'translations.tr.title'       => ['nullable', 'string', 'max:255'],
        ];
    }
}
