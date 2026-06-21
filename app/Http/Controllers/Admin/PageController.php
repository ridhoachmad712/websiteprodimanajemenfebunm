<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Menu;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        return view('frontend.admin-pages-index', [
            'pages' => Page::orderBy('title')->get(),
        ]);
    }

    public function create(): View
    {
        return view('frontend.admin-page-create', [
            'templates' => $this->templates(),
        ]);
    }

    public function store(StorePageRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $template = $validated['template'];

        $page = Page::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'content' => $template === 'content' ? clean($validated['content'] ?? '') : null,
            'status' => $validated['status'] ?? 'published',
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'og_image' => $request->file('og_image')?->store('pages/og', 'public'),
            'sections' => match ($template) {
                'sections' => $this->sectionsFromRequest($request->input('sections', [])),
                'landing', 'documents', 'faq', 'timeline', 'embed' => $this->templateDataFromRequest($template, $request->input('template_data', []), $request),
                default => null,
            },
        ]);

        if ($request->boolean('add_to_menu')) {
            Menu::create([
                'title' => ($validated['menu_title'] ?? null) ?: $page->title,
                'url' => parse_url($page->publicUrl(), PHP_URL_PATH) ?: '/halaman/'.$page->slug,
                'target' => '_self',
                'urutan' => (Menu::max('urutan') ?? 0) + 1,
                'aktif' => true,
            ]);
        }

        return redirect()->route('admin.pages.edit', $page)
            ->with('status', 'Halaman "'.$page->title.'" berhasil dibuat.');
    }

    public function edit(Page $page): View
    {
        // Halaman ber-section (mis. Profil) memakai editor section khusus.
        $view = $page->sections ? 'frontend.admin-page-edit-template' : 'frontend.admin-page-edit-content';

        return view($view, ['page' => $page, 'templates' => $this->templates()]);
    }

    public function update(UpdatePageRequest $request, Page $page): RedirectResponse
    {
        $validated = $request->validated();
        $data = [
            'title' => $validated['title'],
            'status' => $validated['status'] ?? $page->status ?? 'published',
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
        ];

        if ($request->hasFile('og_image')) {
            if ($page->og_image) {
                Storage::disk('public')->delete($page->og_image);
            }
            $data['og_image'] = $request->file('og_image')->store('pages/og', 'public');
        }

        if ($page->sections) {
            $template = $page->template();
            $data['sections'] = $template === 'sections'
                ? $this->sectionsFromRequest($request->input('sections', []))
                : $this->templateDataFromRequest($template, $request->input('template_data', []), $request, $page->sections['_data'] ?? []);
        } else {
            $data['content'] = clean($request->validated('content') ?? ''); // sanitasi HTML
        }

        $page->update($data);

        return redirect()->route('admin.pages.index')
            ->with('status', 'Halaman "'.$page->title.'" berhasil diperbarui.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        if (in_array($page->slug, ['profil', 'hubungi-kami'], true)) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'Halaman inti tidak dapat dihapus.');
        }

        $title = $page->title;
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('status', 'Halaman "'.$title.'" berhasil dihapus.');
    }

    /**
     * @return array<string, array{label: string, description: string, icon: string}>
     */
    private function templates(): array
    {
        return [
            'content' => [
                'label' => 'Konten Tunggal',
                'description' => 'Satu editor bebas untuk halaman artikel, informasi, landing sederhana, atau konten HTML panjang.',
                'icon' => 'ti-file-text',
            ],
            'sections' => [
                'label' => 'Ber-section',
                'description' => 'Halaman dengan daftar isi dan banyak section custom, cocok untuk profil, panduan, SOP, atau kurikulum.',
                'icon' => 'ti-layout-list',
            ],
            'landing' => [
                'label' => 'Landing Page',
                'description' => 'Hero, CTA, dan highlight untuk event, program unggulan, atau promosi khusus.',
                'icon' => 'ti-rocket',
            ],
            'documents' => [
                'label' => 'Dokumen / Download',
                'description' => 'Daftar file, SOP, RPS, SK, formulir, atau tautan unduhan yang dikelompokkan rapi.',
                'icon' => 'ti-download',
            ],
            'faq' => [
                'label' => 'FAQ',
                'description' => 'Accordion tanya-jawab untuk layanan akademik, tugas akhir, atau panduan administrasi.',
                'icon' => 'ti-help-circle',
            ],
            'timeline' => [
                'label' => 'Timeline',
                'description' => 'Alur proses atau sejarah dengan tahapan berurutan.',
                'icon' => 'ti-timeline',
            ],
            'embed' => [
                'label' => 'Embed',
                'description' => 'Sematkan Google Form, Calendar, Map, Drive, YouTube, atau iframe eksternal.',
                'icon' => 'ti-code',
            ],
        ];
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    private function templateDataFromRequest(string $template, array $input, ?\Illuminate\Http\Request $request = null, array $existing = []): array
    {
        $data = ['_template' => $template, '_data' => []];

        if ($template === 'landing') {
            $items = [];
            foreach (($input['items'] ?? []) as $row) {
                if (! is_array($row)) continue;
                if (trim(($row['title'] ?? '').($row['body'] ?? '')) === '') continue;
                $items[] = ['title' => trim((string) ($row['title'] ?? '')), 'body' => trim((string) ($row['body'] ?? ''))];
            }
            $data['_data'] = [
                'eyebrow' => trim((string) ($input['eyebrow'] ?? '')),
                'hero_title' => trim((string) ($input['hero_title'] ?? '')),
                'hero_subtitle' => trim((string) ($input['hero_subtitle'] ?? '')),
                'primary_label' => trim((string) ($input['primary_label'] ?? '')),
                'primary_url' => trim((string) ($input['primary_url'] ?? '')),
                'secondary_label' => trim((string) ($input['secondary_label'] ?? '')),
                'secondary_url' => trim((string) ($input['secondary_url'] ?? '')),
                'items' => $items,
                'body' => clean($input['body'] ?? ''),
            ];
        }

        if ($template === 'documents') {
            $items = [];
            foreach (($input['items'] ?? []) as $i => $row) {
                if (! is_array($row)) continue;
                $url = trim((string) ($row['url'] ?? ''));
                if ($request?->hasFile("template_data.items.$i.file")) {
                    $path = $request->file("template_data.items.$i.file")->store('pages/documents', 'public');
                    $url = Storage::url($path);
                } elseif ($url === '' && isset($existing['items'][$i]['url'])) {
                    $url = $existing['items'][$i]['url'];
                }
                if (trim(($row['label'] ?? '').$url) === '') continue;
                $items[] = [
                    'category' => trim((string) ($row['category'] ?? '')),
                    'label' => trim((string) ($row['label'] ?? '')),
                    'description' => trim((string) ($row['description'] ?? '')),
                    'url' => $url,
                ];
            }
            $data['_data'] = ['intro' => clean($input['intro'] ?? ''), 'items' => $items];
        }

        if ($template === 'faq') {
            $items = [];
            foreach (($input['items'] ?? []) as $row) {
                if (! is_array($row)) continue;
                if (trim(($row['question'] ?? '').($row['answer'] ?? '')) === '') continue;
                $items[] = ['question' => trim((string) ($row['question'] ?? '')), 'answer' => clean($row['answer'] ?? '')];
            }
            $data['_data'] = ['intro' => clean($input['intro'] ?? ''), 'items' => $items];
        }

        if ($template === 'timeline') {
            $items = [];
            foreach (($input['items'] ?? []) as $row) {
                if (! is_array($row)) continue;
                if (trim(($row['title'] ?? '').($row['body'] ?? '')) === '') continue;
                $items[] = [
                    'date' => trim((string) ($row['date'] ?? '')),
                    'title' => trim((string) ($row['title'] ?? '')),
                    'body' => clean($row['body'] ?? ''),
                ];
            }
            $data['_data'] = ['intro' => clean($input['intro'] ?? ''), 'items' => $items];
        }

        if ($template === 'embed') {
            $data['_data'] = [
                'intro' => clean($input['intro'] ?? ''),
                'embed_url' => trim((string) ($input['embed_url'] ?? '')),
                'height' => max(300, min(1200, (int) ($input['height'] ?? 640))),
            ];
        }

        return $data;
    }

    /**
     * @param array<int|string, mixed> $input
     * @return array<string, array{judul: string, isi: string}>
     */
    private function sectionsFromRequest(array $input): array
    {
        $sections = [];

        foreach ($input as $idx => $row) {
            if (! is_array($row)) {
                continue;
            }

            $title = trim((string) ($row['judul'] ?? ''));
            $body = trim((string) ($row['isi'] ?? ''));
            $key = trim((string) ($row['key'] ?? ''));

            if ($title === '' && $body === '') {
                continue;
            }

            if ($key === '') {
                $key = Str::slug($title ?: 'section-'.$idx);
            }

            $baseKey = $key;
            $counter = 2;
            while (isset($sections[$key])) {
                $key = $baseKey.'-'.$counter++;
            }

            $sections[$key] = [
                'judul' => $title ?: Str::headline($key),
                'isi' => clean($body),
            ];
        }

        return $sections;
    }
}
