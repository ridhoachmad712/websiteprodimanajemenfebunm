<?php

namespace Tests\Feature;

use App\Models\Prestasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrestasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_prestasi_page_lists_published_and_hides_draft(): void
    {
        Prestasi::create(['judul' => 'Juara Nasional', 'kategori' => 'mahasiswa', 'tingkat' => 'nasional', 'tanggal' => '2026-01-01', 'status' => 'published']);
        Prestasi::create(['judul' => 'Prestasi Draft', 'kategori' => 'dosen', 'tanggal' => '2026-01-02', 'status' => 'draft']);

        $this->get('/prestasi')
            ->assertOk()
            ->assertSee('Juara Nasional')
            ->assertDontSee('Prestasi Draft');
    }

    public function test_published_prestasi_has_detail_page_but_draft_does_not(): void
    {
        $published = Prestasi::create(['judul' => 'Juara Nasional', 'kategori' => 'mahasiswa', 'tanggal' => '2026-01-01', 'peraih' => 'Budi', 'status' => 'published']);
        $draft = Prestasi::create(['judul' => 'Prestasi Draft', 'kategori' => 'dosen', 'tanggal' => '2026-01-02', 'status' => 'draft']);

        $this->get('/prestasi')->assertOk()->assertSee(route('prestasi.show', $published));
        $this->get(route('prestasi.show', $published))->assertOk()->assertSee('Juara Nasional')->assertSee('Budi');
        $this->get(route('prestasi.show', $draft))->assertNotFound();
    }

    public function test_public_page_filters_by_kategori(): void
    {
        Prestasi::create(['judul' => 'Prestasi Mahasiswa', 'kategori' => 'mahasiswa', 'tanggal' => '2026-01-01', 'status' => 'published']);
        Prestasi::create(['judul' => 'Prestasi Dosen', 'kategori' => 'dosen', 'tanggal' => '2026-01-02', 'status' => 'published']);

        $this->get('/prestasi?kategori=dosen')
            ->assertOk()
            ->assertSee('Prestasi Dosen')
            ->assertDontSee('Prestasi Mahasiswa');
    }

    public function test_public_page_combines_category_and_level_filters(): void
    {
        Prestasi::create(['judul' => 'Mahasiswa Nasional', 'kategori' => 'mahasiswa', 'tingkat' => 'nasional', 'tanggal' => '2026-01-01', 'status' => 'published']);
        Prestasi::create(['judul' => 'Mahasiswa Lokal', 'kategori' => 'mahasiswa', 'tingkat' => 'lokal', 'tanggal' => '2026-01-02', 'status' => 'published']);
        Prestasi::create(['judul' => 'Dosen Nasional', 'kategori' => 'dosen', 'tingkat' => 'nasional', 'tanggal' => '2026-01-03', 'status' => 'published']);

        $this->get('/prestasi?kategori=mahasiswa&tingkat=nasional')->assertOk()
            ->assertSee('Mahasiswa Nasional')
            ->assertDontSee('Mahasiswa Lokal')
            ->assertDontSee('Dosen Nasional')
            ->assertSee('value="mahasiswa" selected', false)
            ->assertSee('value="nasional" selected', false);
    }

    public function test_guest_cannot_access_admin_prestasi(): void
    {
        $this->get('/admin/prestasi')->assertRedirect('/login');
    }

    public function test_admin_can_create_and_delete_prestasi(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->get('/admin/prestasi/create')->assertOk();

        $this->actingAs($admin)->post('/admin/prestasi', [
            'judul' => 'Juara 1 Lomba X',
            'kategori' => 'mahasiswa',
            'tingkat' => 'nasional',
            'peraih' => 'Budi',
            'tanggal' => '2026-05-01',
            'status' => 'published',
        ])->assertRedirect(route('admin.prestasi.index'));

        $prestasi = Prestasi::where('judul', 'Juara 1 Lomba X')->firstOrFail();
        $this->assertSame('nasional', $prestasi->tingkat);

        $this->actingAs($admin)->delete(route('admin.prestasi.destroy', $prestasi))
            ->assertRedirect(route('admin.prestasi.index'));
        $this->assertDatabaseMissing('prestasi', ['id' => $prestasi->id]);
    }
}
