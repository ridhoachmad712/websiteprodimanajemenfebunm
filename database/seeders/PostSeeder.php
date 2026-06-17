<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (! $user) {
            return; // butuh minimal satu user sebagai penulis
        }

        $berita     = Category::where('slug', 'berita-informasi')->first();
        $artikel    = Category::where('slug', 'artikel')->first();
        $prestasi   = Category::where('slug', 'prestasi')->first();
        $pengumuman = Category::where('slug', 'pengumuman')->first();

        $posts = [
            [
                'judul'   => 'Selamat Datang di Website Baru Prodi Manajemen FEB UNM',
                'excerpt' => 'Website resmi Program Studi Manajemen kini hadir dengan tampilan baru yang lebih modern dan cepat.',
                'kategori'=> [$berita, $pengumuman],
            ],
            [
                'judul'   => 'Mahasiswa Manajemen Raih Juara Kompetisi Bisnis Nasional',
                'excerpt' => 'Tim mahasiswa Prodi Manajemen berhasil meraih prestasi membanggakan di tingkat nasional.',
                'kategori'=> [$prestasi, $berita],
            ],
            [
                'judul'   => 'Strategi Manajemen Keuangan di Era Digital',
                'excerpt' => 'Artikel singkat mengenai pengelolaan keuangan yang adaptif terhadap perubahan teknologi.',
                'kategori'=> [$artikel],
            ],
        ];

        foreach ($posts as $i => $row) {
            $post = Post::updateOrCreate(
                ['slug' => Str::slug($row['judul'])],
                [
                    'judul'        => $row['judul'],
                    'excerpt'      => $row['excerpt'],
                    'konten'       => '<p>'.$row['excerpt'].'</p><p>Konten lengkap akan diisi melalui panel admin.</p>',
                    'user_id'      => $user->id,
                    'status'       => 'published',
                    'published_at' => now()->subDays(count($posts) - $i),
                ],
            );

            $ids = collect($row['kategori'])->filter()->pluck('id')->all();
            $post->categories()->sync($ids);
        }
    }
}
