<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCreditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'title_id' => ['required', 'integer', 'exists:titles,id'],
            'person_id' => ['required', 'integer', 'exists:people,id'],
            'role_type' => ['required', Rule::in(['actor', 'director', 'creator'])],
            'character_name' => ['nullable', 'string', 'max:255'],
            'billing_order' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
