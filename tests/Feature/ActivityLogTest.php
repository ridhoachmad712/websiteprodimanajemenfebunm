<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Prestasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_update_delete_are_recorded_with_actor(): void
    {
        $admin = User::factory()->create(['name' => 'Pak Admin']);
        $this->actingAs($admin);

        $p = Prestasi::create(['judul' => 'Juara', 'kategori' => 'mahasiswa', 'tanggal' => '2026-01-01', 'status' => 'published']);
        $p->update(['judul' => 'Juara Diubah']);
        $p->delete();

        $logs = ActivityLog::where('subject_type', 'Prestasi')->get();
        $this->assertEqualsCanonicalizing(['created', 'updated', 'deleted'], $logs->pluck('action')->all());

        $created = $logs->firstWhere('action', 'created');
        $this->assertSame($admin->id, $created->user_id);
        $this->assertSame('Pak Admin', $created->user_name);
        $this->assertSame('Juara', $created->subject_label);
    }

    public function test_viewing_a_post_does_not_create_a_log(): void
    {
        $post = \App\Models\Post::create([
            'judul' => 'Berita', 'slug' => 'berita', 'konten' => '<p>x</p>',
            'user_id' => User::factory()->create()->id,
            'status' => 'published', 'published_at' => now()->subDay(),
        ]);

        $before = ActivityLog::count();
        $this->get($post->url())->assertOk();

        // Penghitung "dilihat" bertambah tanpa menambah log aktivitas.
        $this->assertSame(1, (int) $post->fresh()->dilihat);
        $this->assertSame($before, ActivityLog::count());
    }

    public function test_admin_can_view_log_page_editor_cannot(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin/aktivitas')->assertOk();
        $this->actingAs(User::factory()->editor()->create())->get('/admin/aktivitas')->assertForbidden();
    }
}
