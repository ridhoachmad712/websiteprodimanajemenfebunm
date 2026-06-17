<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title'             => ['required', 'string', 'max:255'],
            'content'           => ['nullable', 'string'],
            'sections'          => ['nullable', 'array'],
            'sections.*.judul'  => ['nullable', 'string', 'max:255'],
            'sections.*.isi'    => ['nullable', 'string'],
        ];
    }
}
