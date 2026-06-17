<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Unggah banyak gambar sekaligus; tiap gambar menjadi satu item galeri.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'judul'    => ['nullable', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'gambar'   => ['required', 'array', 'min:1'],
            'gambar.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return ['gambar.*' => 'gambar'];
    }
}
