<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Pindahkan Post berkategori "pengumuman" ke tabel `pengumuman` yang berdiri
 * sendiri, lalu bersihkan post + kategori lamanya. Aman pada instalasi baru
 * (tidak ada data → tidak melakukan apa-apa).
 */
return new class extends Migration
{
    public function up(): void
    {
        $categoryId = DB::table('categories')->where('slug', 'pengumuman')->value('id');
        if (! $categoryId) {
            return;
        }

        $postIds = DB::table('category_post')->where('category_id', $categoryId)->pluck('post_id');

        foreach (DB::table('posts')->whereIn('id', $postIds)->get() as $post) {
            DB::table('pengumuman')->insert([
                'judul' => $post->judul,
                'slug' => $post->slug,
                'konten' => $post->konten ?? '',
                'status' => $post->status ?? 'draft',
                'published_at' => $post->published_at,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
            ]);
        }

        // Hapus relasi + post + kategori pengumuman.
        DB::table('category_post')->where('category_id', $categoryId)->delete();
        DB::table('posts')->whereIn('id', $postIds)->delete();
        DB::table('categories')->where('id', $categoryId)->delete();
    }

    public function down(): void
    {
        // Tidak dipulihkan otomatis (data pindah satu arah).
    }
};
