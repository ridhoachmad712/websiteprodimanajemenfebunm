<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Edit satu item: judul/kategori/urutan, ganti gambar opsional.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'judul'    => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'urutan'   => ['nullable', 'integer', 'min:0'],
            'gambar'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
