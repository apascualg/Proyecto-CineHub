<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTitleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['movie', 'series'])],
            'title' => ['required', 'string', 'max:255'],
            'original_title' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:titles,slug'],
            'synopsis' => ['nullable', 'string'],
            'release_date' => ['nullable', 'date'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'seasons_count' => ['nullable', 'integer', 'min:1'],
            'episodes_count' => ['nullable', 'integer', 'min:1'],
            'poster_path' => ['nullable', 'string', 'max:255'],
            'backdrop_path' => ['nullable', 'string', 'max:255'],
            'trailer_url' => ['nullable', 'url', 'max:500'],
            'streaming_providers' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(['published', 'draft'])],
            'genres' => ['nullable', 'array'],
            'genres.*' => ['integer', 'exists:genres,id'],
        ];
    }
}
