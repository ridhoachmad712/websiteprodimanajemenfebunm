<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
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
            'judul'          => ['required', 'string', 'max:255'],
            'excerpt'        => ['nullable', 'string', 'max:500'],
            'konten'         => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status'         => ['required', Rule::in(['draft', 'published'])],
            'published_at'   => ['nullable', 'date'],
            'categories'     => ['nullable', 'array'],
            'categories.*'   => ['integer', 'exists:categories,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'judul'          => 'judul',
            'konten'         => 'isi berita',
            'featured_image' => 'gambar utama',
            'published_at'   => 'tanggal terbit',
        ];
    }
}
