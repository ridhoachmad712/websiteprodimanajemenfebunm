<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Terima unggahan gambar dari editor WYSIWYG (TinyMCE) dan kembalikan
     * URL absolut gambar dalam format { "location": "..." }.
     */
    public function image(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
        ]);

        $path = $request->file('file')->store('posts/inline', 'public');

        // URL absolut agar aman dari sanitasi HTMLPurifier saat konten disimpan.
        return response()->json([
            'location' => url(Storage::url($path)),
        ]);
    }
}
