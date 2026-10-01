<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DosenJabatanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_save_jabatan(): void
    {
        $this->actingAs(User::factory()->create())->post('/admin/dosen', [
            'nama' => 'Dr. Anwar, S.E., M.Si.',
            'jabatan' => 'Ketua Program Studi',
            'kategori' => 'tetap_prodi',
        ])->assertRedirect(route('admin.dosen.index'));

        $this->assertDatabaseHas('dosen', ['nama' => 'Dr. Anwar, S.E., M.Si.', 'jabatan' => 'Ketua Program Studi']);
    }

    public function test_jabatan_shows_on_public_list_and_detail(): void
    {
        $dosen = Dosen::create([
            'nama' => 'Prof. Budi', 'slug' => 'prof-budi', 'kategori' => 'guru_besar',
            'jabatan' => 'Wakil Dekan I', 'urutan' => 1,
        ]);

        $this->get('/daftar-dosen')->assertOk()
            ->assertSee('Wakil Dekan I')
            ->assertSeeInOrder(['class="dosen-photo"', 'class="dosen-role-overlay"', 'class="card-body"'], false);
        $this->get(route('dosen.show', $dosen))->assertOk()->assertSee('Wakil Dekan I');
    }

    public function test_jabatan_is_optional(): void
    {
        $this->actingAs(User::factory()->create())->post('/admin/dosen', [
            'nama' => 'Dosen Tanpa Jabatan',
            'kategori' => 'tetap_prodi',
        ])->assertRedirect(route('admin.dosen.index'));

        $this->assertDatabaseHas('dosen', ['nama' => 'Dosen Tanpa Jabatan', 'jabatan' => null]);
    }
}
