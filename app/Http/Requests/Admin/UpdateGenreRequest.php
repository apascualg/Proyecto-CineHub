<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class UpdateGenreRequest extends StoreGenreRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('genres', 'slug')->ignore($this->route('genre'))],
        ];
    }
}
