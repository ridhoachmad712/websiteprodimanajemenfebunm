<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_editor_cannot_access_admin_only_pages(): void
    {
        $editor = User::factory()->editor()->create();

        foreach (['/admin/settings', '/admin/tampilan', '/admin/menus', '/admin/beranda', '/admin/users'] as $url) {
            $this->actingAs($editor)->get($url)->assertForbidden();
        }
    }

    public function test_admin_can_access_admin_only_pages(): void
    {
        $admin = User::factory()->create(); // default role admin

        $this->actingAs($admin)->get('/admin/settings')->assertOk();
        $this->actingAs($admin)->get('/admin/users')->assertOk();
    }

    public function test_editor_can_access_content_modules(): void
    {
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)->get('/admin/posts')->assertOk();
        $this->actingAs($editor)->get('/admin/prestasi')->assertOk();
        $this->actingAs($editor)->get('/admin')->assertOk(); // dashboard
    }

    public function test_editor_can_edit_own_account_but_not_others(): void
    {
        $editor = User::factory()->editor()->create();
        $other = User::factory()->create();

        $this->actingAs($editor)->get(route('admin.users.edit', $editor))->assertOk();
        $this->actingAs($editor)->get(route('admin.users.edit', $other))->assertForbidden();
    }

    public function test_editor_cannot_escalate_own_role(): void
    {
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)->put(route('admin.users.update', $editor), [
            'name' => 'Editor Updated',
            'email' => $editor->email,
            'role' => 'admin',
        ])->assertRedirect();

        $this->assertSame('editor', $editor->fresh()->role);
    }

    public function test_cannot_downgrade_the_last_admin(): void
    {
        $admin = User::factory()->create(); // satu-satunya admin

        $this->actingAs($admin)->put(route('admin.users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'editor',
        ])->assertSessionHas('error');

        $this->assertSame('admin', $admin->fresh()->role);
    }
}
