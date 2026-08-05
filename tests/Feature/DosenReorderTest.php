<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DosenReorderTest extends TestCase
{
    use RefreshDatabase;

    public function test_reorder_updates_urutan_to_match_posted_order(): void
    {
        $a = Dosen::create(['nama' => 'Dosen A', 'slug' => 'dosen-a', 'kategori' => 'tetap_prodi', 'urutan' => 1]);
        $b = Dosen::create(['nama' => 'Dosen B', 'slug' => 'dosen-b', 'kategori' => 'tetap_prodi', 'urutan' => 2]);
        $c = Dosen::create(['nama' => 'Dosen C', 'slug' => 'dosen-c', 'kategori' => 'guru_besar', 'urutan' => 3]);

        $this->actingAs(User::factory()->create())
            ->postJson(route('admin.dosen.reorder'), ['ids' => [$c->id, $a->id, $b->id]])
            ->assertOk()
            ->assertJson(['ok' => true, 'count' => 3]);

        $this->assertSame(1, (int) $c->fresh()->urutan);
        $this->assertSame(2, (int) $a->fresh()->urutan);
        $this->assertSame(3, (int) $b->fresh()->urutan);
    }

    public function test_public_list_follows_the_saved_order(): void
    {
        $a = Dosen::create(['nama' => 'Andi', 'slug' => 'andi', 'kategori' => 'tetap_prodi', 'urutan' => 5]);
        $b = Dosen::create(['nama' => 'Budi', 'slug' => 'budi', 'kategori' => 'tetap_prodi', 'urutan' => 6]);

        // Susun ulang: Budi sebelum Andi.
        $this->actingAs(User::factory()->create())
            ->postJson(route('admin.dosen.reorder'), ['ids' => [$b->id, $a->id]])->assertOk();

        $html = $this->get('/daftar-dosen')->assertOk()->getContent();
        $this->assertLessThan(strpos($html, 'Andi'), strpos($html, 'Budi'), 'Budi harus tampil sebelum Andi');
    }

    public function test_admin_index_renders_sortable_list(): void
    {
        Dosen::create(['nama' => 'Dosen X', 'slug' => 'dosen-x', 'kategori' => 'tetap_prodi', 'urutan' => 1]);

        $this->actingAs(User::factory()->create())->get('/admin/dosen')
            ->assertOk()
            ->assertSee('dosen-sortable')
            ->assertSee('Dosen X');
    }

    public function test_guest_cannot_reorder(): void
    {
        $this->post(route('admin.dosen.reorder'), ['ids' => []])->assertRedirect('/login');
    }
}
