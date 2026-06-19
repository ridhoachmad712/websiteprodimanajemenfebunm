<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // form publik
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nama'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'subjek'  => ['nullable', 'string', 'max:255'],
            'pesan'   => ['required', 'string', 'max:5000'],
            // Honeypot anti-spam: harus kosong (diisi bot).
            'website' => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'website.prohibited' => 'Pengiriman ditolak.',
        ];
    }

    public function attributes(): array
    {
        return ['pesan' => 'pesan', 'nama' => 'nama'];
    }
}
