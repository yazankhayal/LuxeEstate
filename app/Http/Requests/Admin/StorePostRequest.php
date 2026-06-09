<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $postId = $this->route('id');

        return [
            'slug'                        => ['required', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($postId)],
            'category_id'                 => ['required', 'exists:categories,id'],
            'tag_ids'                     => ['nullable', 'array'],
            'tag_ids.*'                   => ['exists:tags,id'],
            'is_published'                => ['boolean'],
            'published_at'                => ['nullable', 'date'],
            'featured_image'              => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'meta_title'                  => ['nullable', 'string', 'max:160'],
            'meta_description'            => ['nullable', 'string', 'max:320'],
            'meta_keywords'               => ['nullable', 'string', 'max:255'],
            'translations'                => ['required', 'array'],
            'translations.en'             => ['required', 'array'],
            'translations.en.title'       => ['required', 'string', 'max:255'],
            'translations.en.excerpt'     => ['nullable', 'string', 'max:500'],
            'translations.en.content'     => ['required', 'string'],
            'translations.ar.title'       => ['nullable', 'string', 'max:255'],
            'translations.ar.content'     => ['nullable', 'string'],
            'translations.tr.title'       => ['nullable', 'string', 'max:255'],
            'translations.tr.content'     => ['nullable', 'string'],
        ];
    }
}
