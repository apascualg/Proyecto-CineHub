<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTitleRequest extends StoreTitleRequest
{
    public function rules(): array
    {
        return array_replace(
            parent::rules(),
            ['slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('titles', 'slug')->ignore($this->route('title'))]],
        );
    }
}
