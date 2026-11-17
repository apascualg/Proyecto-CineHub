<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'biography' => ['nullable', 'string'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'photo_path' => ['nullable', 'string', 'max:255'],
        ];
    }
}
