<?php

namespace App\Http\Requests\Admin;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $propertyId = $this->route('id');

        return [
            'slug'             => ['required', 'string', 'max:255', Rule::unique('properties', 'slug')->ignore($propertyId)],
            'type'             => ['required', new Enum(PropertyType::class)],
            'status'           => ['required', new Enum(PropertyStatus::class)],
            'price'            => ['required', 'numeric', 'min:0'],
            'currency'         => ['required', 'string', 'size:3'],
            'location'         => ['required', 'string', 'max:255'],
            'city'             => ['required', 'string', 'max:100'],
            'country'          => ['required', 'string', 'max:100'],
            'area'             => ['required', 'numeric', 'min:0'],
            'bedrooms'         => ['nullable', 'integer', 'min:0'],
            'bathrooms'        => ['nullable', 'integer', 'min:0'],
            'is_featured'      => ['boolean'],
            'latitude'         => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'        => ['nullable', 'numeric', 'between:-180,180'],
            'whatsapp'         => ['nullable', 'string', 'max:30'],
            'phone'            => ['nullable', 'string', 'max:30'],
            'video_url'        => ['nullable', 'url', 'max:500'],
            'meta_title'       => ['nullable', 'string', 'max:160'],
            'meta_description' => ['nullable', 'string', 'max:320'],
            'meta_keywords'    => ['nullable', 'string', 'max:255'],
            // Translations
            'translations'                 => ['required', 'array'],
            'translations.en'              => ['required', 'array'],
            'translations.en.title'        => ['required', 'string', 'max:255'],
            'translations.en.description'  => ['required', 'string'],
            'translations.en.address'      => ['nullable', 'string', 'max:500'],
            'translations.en.features'     => ['nullable', 'array'],
            'translations.ar'              => ['nullable', 'array'],
            'translations.ar.title'        => ['nullable', 'string', 'max:255'],
            'translations.ar.description'  => ['nullable', 'string'],
            'translations.tr'              => ['nullable', 'array'],
            'translations.tr.title'        => ['nullable', 'string', 'max:255'],
            'translations.tr.description'  => ['nullable', 'string'],
            // Images
            'images'           => ['nullable', 'array'],
            'images.*'         => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'translations.en.title.required'       => 'The English title is required.',
            'translations.en.description.required'  => 'The English description is required.',
        ];
    }
}
