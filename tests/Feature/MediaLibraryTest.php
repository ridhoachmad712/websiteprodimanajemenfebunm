<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaLibraryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sees_uploaded_files_editor_is_forbidden(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('posts/contoh.png', 'x');

        $this->actingAs(User::factory()->create())->get('/admin/media')
            ->assertOk()
            ->assertSee('contoh.png');

        $this->actingAs(User::factory()->editor()->create())->get('/admin/media')->assertForbidden();
    }

    public function test_admin_can_delete_a_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('mitra/logo.png', 'x');

        $this->actingAs(User::factory()->create())
            ->delete(route('admin.media.destroy'), ['path' => 'mitra/logo.png'])
            ->assertRedirect();

        Storage::disk('public')->assertMissing('mitra/logo.png');
    }

    public function test_path_traversal_is_rejected(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->create())
            ->delete(route('admin.media.destroy'), ['path' => '../../.env'])
            ->assertSessionHas('error');
    }
}
