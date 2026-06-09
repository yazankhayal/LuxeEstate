<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'site_name'        => ['required', 'string', 'max:100'],
            'site_description' => ['nullable', 'string', 'max:500'],
            'phone'            => ['nullable', 'string', 'max:30'],
            'email'            => ['nullable', 'email', 'max:100'],
            'address'          => ['nullable', 'string', 'max:500'],
            'whatsapp'         => ['nullable', 'string', 'max:30'],
            'facebook'         => ['nullable', 'url', 'max:255'],
            'instagram'        => ['nullable', 'url', 'max:255'],
            'twitter'          => ['nullable', 'url', 'max:255'],
            'linkedin'         => ['nullable', 'url', 'max:255'],
            'youtube'          => ['nullable', 'url', 'max:255'],
            'default_locale'   => ['required', 'string', 'size:2'],
            'meta_title'       => ['nullable', 'string', 'max:160'],
            'meta_description' => ['nullable', 'string', 'max:320'],
            'meta_keywords'    => ['nullable', 'string', 'max:255'],
            'logo'             => ['nullable', 'file', 'image', 'mimes:png,svg,webp', 'max:1024'],
            'favicon'          => ['nullable', 'file', 'image', 'mimes:png,ico,svg', 'max:512'],
        ];
    }
}
