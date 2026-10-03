<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DosenWritingTest extends TestCase
{
    use RefreshDatabase;

    public function test_dosen_can_save_submit_and_have_an_editor_publish_an_opinion(): void
    {
        $dosen = User::factory()->dosen()->create(['name' => 'Dr. Siti Aminah']);
        $editor = User::factory()->editor()->create();

        $this->actingAs($dosen)->get('/admin')->assertOk()->assertSee('Tulisan Saya');
        $this->actingAs($dosen)->get(route('admin.writings.create'))->assertOk();
        $this->actingAs($dosen)->post(route('admin.writings.store'), [
            'judul' => 'Masa Depan Manajemen',
            'jenis' => 'opini',
            'excerpt' => 'Pandangan tentang praktik manajemen.',
            'konten' => '<p>Isi tulisan dosen.</p><script>alert(1)</script>',
            'status' => 'published',
            'user_id' => $editor->id,
        ])->assertRedirect(route('admin.writings.index'));

        $post = Post::where('judul', 'Masa Depan Manajemen')->firstOrFail();
        $this->assertSame($dosen->id, $post->user_id);
        $this->assertSame('draft', $post->status);
        $this->assertSame('opini', $post->jenis);
        $this->assertStringNotContainsString('<script>', $post->konten);

        $this->get('/tulisan')->assertDontSee('Masa Depan Manajemen');
        $this->get('/berita')->assertDontSee('Masa Depan Manajemen');

        $this->actingAs($dosen)->post(route('admin.writings.submit', $post))->assertRedirect();
        $this->assertNotNull($post->fresh()->submitted_at);
        $this->actingAs($dosen)->get(route('admin.writings.edit', $post))->assertForbidden();
        $this->actingAs($editor)->get(route('admin.posts.index', ['status' => 'menunggu']))
            ->assertOk()->assertSee('Masa Depan Manajemen')->assertSee('Dr. Siti Aminah');

        $this->actingAs($editor)->put(route('admin.posts.update', $post), [
            'judul' => $post->judul,
            'jenis' => 'opini',
            'konten' => $post->konten,
            'status' => 'published',
        ])->assertRedirect(route('admin.posts.index'));

        $post->refresh();
        $this->assertSame('published', $post->status);
        $this->assertNull($post->submitted_at);
        $this->get('/tulisan')->assertOk()->assertSee('Masa Depan Manajemen');
        $this->get('/tulisan?jenis=artikel')->assertDontSee('Masa Depan Manajemen');
        $this->get('/berita')->assertDontSee('Masa Depan Manajemen');
        $this->get($post->url())->assertOk()->assertSee('Dr. Siti Aminah')->assertSee('Tulisan Dosen');
        $this->get('/cari?q=Masa+Depan')->assertOk()->assertSee('Tulisan Dosen')->assertSee($post->url());
        $this->actingAs($dosen)->put(route('admin.writings.update', $post), [
            'judul' => 'Diubah', 'jenis' => 'artikel', 'konten' => 'Isi baru',
        ])->assertForbidden();
    }

    public function test_dosen_can_edit_only_own_draft_and_editor_can_return_submission(): void
    {
        $dosen = User::factory()->dosen()->create();
        $other = User::factory()->dosen()->create();
        $editor = User::factory()->editor()->create();
        $draft = Post::create(['judul' => 'Draft Asli', 'slug' => 'draft-asli', 'jenis' => 'artikel',
            'konten' => '<p>Isi</p>', 'user_id' => $dosen->id, 'status' => 'draft']);
        $otherDraft = Post::create(['judul' => 'Draft Lain', 'slug' => 'draft-lain', 'jenis' => 'artikel',
            'konten' => '<p>Isi</p>', 'user_id' => $other->id, 'status' => 'draft']);

        $this->actingAs($dosen)->get(route('admin.writings.edit', $otherDraft))->assertForbidden();
        $this->actingAs($dosen)->put(route('admin.writings.update', $otherDraft), [
            'judul' => 'Diambil', 'jenis' => 'opini', 'konten' => 'Isi',
        ])->assertForbidden();
        $this->actingAs($dosen)->get(route('admin.writings.edit', $draft))->assertOk();
        $this->actingAs($dosen)->put(route('admin.writings.update', $draft), [
            'judul' => 'Draft Diperbarui', 'jenis' => 'opini', 'konten' => '<p>Isi baru</p>',
        ])->assertRedirect();
        $this->assertSame('draft-diperbarui', $draft->fresh()->slug);
        $draft->refresh();

        $this->actingAs($dosen)->post(route('admin.writings.submit', $draft))->assertRedirect();
        $this->actingAs($dosen)->post(route('admin.writings.withdraw', $draft))->assertRedirect();
        $this->assertNull($draft->fresh()->submitted_at);
        $this->actingAs($dosen)->post(route('admin.writings.submit', $draft))->assertRedirect();
        $this->actingAs($editor)->post(route('admin.posts.return-to-draft', $draft))->assertRedirect();
        $this->assertNull($draft->fresh()->submitted_at);
        $this->actingAs($dosen)->get(route('admin.writings.edit', $draft))->assertOk();
    }

    public function test_dosen_cannot_access_other_content_modules_or_publish_directly(): void
    {
        $dosen = User::factory()->dosen()->create();
        $post = Post::create(['judul' => 'Berita Editor', 'slug' => 'berita-editor', 'konten' => 'Isi',
            'user_id' => User::factory()->editor()->create()->id, 'status' => 'draft']);

        foreach (['/admin/posts', '/admin/dosen', '/admin/settings', '/admin/pengumuman', '/admin/pesan'] as $url) {
            $this->actingAs($dosen)->get($url)->assertForbidden();
        }
        $this->actingAs($dosen)->put(route('admin.posts.update', $post), [
            'judul' => 'Diubah', 'konten' => 'Isi', 'status' => 'published',
        ])->assertForbidden();
        $this->actingAs($dosen)->post(route('admin.uploads.image'))->assertForbidden();
        $this->actingAs(User::factory()->editor()->create())->get(route('admin.writings.index'))->assertForbidden();
        $this->app['auth']->logout();
        $this->get(route('admin.writings.index'))->assertRedirect('/login');
    }

    public function test_admin_can_create_dosen_account_without_downgrading_last_admin(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Dosen Baru',
            'email' => 'dosen@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'dosen',
        ])->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'dosen@example.com', 'role' => 'dosen']);

        $this->actingAs($admin)->put(route('admin.users.update', $admin), [
            'name' => $admin->name, 'email' => $admin->email, 'role' => 'dosen',
        ])->assertSessionHas('error');
        $this->assertSame('admin', $admin->fresh()->role);
    }

    public function test_dosen_cannot_submit_news_or_empty_content(): void
    {
        $dosen = User::factory()->dosen()->create();

        $this->actingAs($dosen)->post(route('admin.writings.store'), [
            'judul' => 'Bukan Berita', 'jenis' => 'berita', 'konten' => '<p>Isi</p>',
        ])->assertSessionHasErrors('jenis');

        $this->actingAs($dosen)->post(route('admin.writings.store'), [
            'judul' => 'Kosong', 'jenis' => 'artikel', 'konten' => '<p><br></p>',
        ])->assertSessionHasErrors('konten');

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_account_with_published_writing_cannot_be_deleted(): void
    {
        $admin = User::factory()->create();
        $dosen = User::factory()->dosen()->create();
        Post::create(['judul' => 'Tulisan Penting', 'slug' => 'tulisan-penting', 'jenis' => 'artikel',
            'konten' => 'Isi', 'user_id' => $dosen->id, 'status' => 'published', 'published_at' => now()]);

        $this->actingAs($admin)->delete(route('admin.users.destroy', $dosen))->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $dosen->id]);
        $this->assertDatabaseHas('posts', ['judul' => 'Tulisan Penting']);
    }

    public function test_dosen_can_upload_editor_image_to_existing_media_storage(): void
    {
        Storage::fake('public');
        $dosen = User::factory()->dosen()->create();

        $this->actingAs($dosen)->postJson(route('admin.writings.uploads.image'), [
            'file' => UploadedFile::fake()->image('diagram.png'),
        ])->assertOk()->assertJsonStructure(['location']);

        $this->assertCount(1, Storage::disk('public')->files('posts/inline'));
    }
}
