<?php

namespace Tests\Feature;

use App\Models\Seminar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SeminarTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_page_splits_upcoming_and_past_and_hides_draft(): void
    {
        Seminar::create(['nama' => 'Mahasiswa Depan', 'judul' => 'Judul A', 'jenis' => 'proposal', 'tanggal' => Carbon::now()->addDays(5), 'status' => 'published']);
        Seminar::create(['nama' => 'Mahasiswa Lalu', 'judul' => 'Judul B', 'jenis' => 'hasil', 'tanggal' => Carbon::now()->subDays(5), 'status' => 'published']);
        Seminar::create(['nama' => 'Mahasiswa Draft', 'judul' => 'Judul C', 'jenis' => 'tutup', 'tanggal' => Carbon::now()->addDays(2), 'status' => 'draft']);

        $this->get('/daftar-seminar')
            ->assertOk()
            ->assertSee('Mahasiswa Depan')
            ->assertSee('Mahasiswa Lalu')
            ->assertDontSee('Mahasiswa Draft');
    }

    public function test_public_page_filters_by_jenis(): void
    {
        Seminar::create(['nama' => 'Peserta Proposal', 'judul' => 'X', 'jenis' => 'proposal', 'tanggal' => Carbon::now()->addDays(3), 'status' => 'published']);
        Seminar::create(['nama' => 'Peserta Hasil', 'judul' => 'Y', 'jenis' => 'hasil', 'tanggal' => Carbon::now()->addDays(4), 'status' => 'published']);

        $this->get('/daftar-seminar?jenis=hasil')
            ->assertOk()
            ->assertSee('Peserta Hasil')
            ->assertDontSee('Peserta Proposal');
    }

    public function test_guest_cannot_access_admin_seminar(): void
    {
        $this->get('/admin/seminar')->assertRedirect('/login');
    }

    public function test_admin_can_create_a_seminar(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->get('/admin/seminar/create')->assertOk();

        $this->actingAs($admin)->post('/admin/seminar', [
            'nama' => 'Joko',
            'nim' => '210901999',
            'judul' => 'Pengaruh Motivasi terhadap Kinerja',
            'jenis' => 'proposal',
            'tanggal' => '2026-09-01T09:00',
            'tempat' => 'Ruang Sidang 1',
            'status' => 'published',
        ])->assertRedirect(route('admin.seminar.index'));

        $this->assertDatabaseHas('seminars', ['nama' => 'Joko', 'jenis' => 'proposal']);
    }
}
