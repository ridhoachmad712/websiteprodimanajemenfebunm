<?php

namespace App\Http\Requests;

use App\Models\Dosen;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDosenRequest extends FormRequest
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
            'nama'        => ['required', 'string', 'max:255'],
            'nip'         => ['nullable', 'string', 'max:50'],
            'kategori'    => ['required', Rule::in(array_keys(Dosen::KATEGORI))],
            'konsentrasi' => ['nullable', 'string', 'max:255'],
            'bio_link'    => ['nullable', 'url', 'max:255'],
            'urutan'      => ['nullable', 'integer', 'min:0'],
            'foto'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama'        => 'nama dosen',
            'bio_link'    => 'tautan bio',
            'foto'        => 'foto',
        ];
    }
}
