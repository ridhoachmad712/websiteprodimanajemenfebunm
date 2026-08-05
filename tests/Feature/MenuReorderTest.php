<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuReorderTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_reorder_menu_and_submenu(): void
    {
        $a = Menu::create(['title' => 'A', 'url' => '/a', 'urutan' => 1, 'aktif' => true]);
        $b = Menu::create(['title' => 'B', 'url' => '/b', 'urutan' => 2, 'aktif' => true]);
        $c1 = Menu::create(['parent_id' => $a->id, 'title' => 'C1', 'url' => '/c1', 'urutan' => 1, 'aktif' => true]);
        $c2 = Menu::create(['parent_id' => $a->id, 'title' => 'C2', 'url' => '/c2', 'urutan' => 2, 'aktif' => true]);

        $this->actingAs(User::factory()->create())
            ->postJson(route('admin.menus.reorder'), ['items' => [
                ['id' => $b->id, 'urutan' => 1],
                ['id' => $a->id, 'urutan' => 2],
                ['id' => $c2->id, 'urutan' => 1],
                ['id' => $c1->id, 'urutan' => 2],
            ]])
            ->assertOk()
            ->assertJson(['ok' => true]);

        $this->assertSame(1, (int) $b->fresh()->urutan);
        $this->assertSame(2, (int) $a->fresh()->urutan);
        $this->assertSame(1, (int) $c2->fresh()->urutan);
        $this->assertSame(2, (int) $c1->fresh()->urutan);
    }

    public function test_index_renders_sortable(): void
    {
        Menu::create(['title' => 'Beranda', 'url' => '/', 'urutan' => 1, 'aktif' => true]);

        $this->actingAs(User::factory()->create())->get('/admin/menus')
            ->assertOk()
            ->assertSee('menu-sortable')
            ->assertSee('Beranda');
    }

    public function test_editor_cannot_reorder(): void
    {
        $this->actingAs(User::factory()->editor()->create())
            ->postJson(route('admin.menus.reorder'), ['items' => []])
            ->assertForbidden();
    }

    public function test_guest_cannot_reorder(): void
    {
        $this->post(route('admin.menus.reorder'), ['items' => []])->assertRedirect('/login');
    }
}
