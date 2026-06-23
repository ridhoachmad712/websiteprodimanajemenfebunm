<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class KalenderController extends Controller
{
    /**
     * Kalender Akademik — tampilan kalender (FullCalendar).
     */
    public function index(): View
    {
        $events = Kegiatan::orderBy('mulai')->get()->map->toEvent()->values()->all();
        $events = array_merge($events, $this->liburNasional());

        return view('frontend.kalender', ['events' => $events]);
    }

    /**
     * Ambil libur nasional dari API publik (di-cache 1 hari). Aman bila API gagal.
     *
     * @return array<int, array<string, mixed>>
     */
    private function liburNasional(): array
    {
        return Cache::remember('libur_nasional', now()->addDay(), function () {
            $events = [];
            foreach ([now()->year, now()->year + 1] as $tahun) {
                try {
                    $res = Http::timeout(6)->get('https://api-hari-libur.vercel.app/api', ['year' => $tahun]);
                    if (! $res->ok()) {
                        continue;
                    }
                    foreach ($res->json('data') ?? [] as $h) {
                        $desc = $h['description'] ?? 'Libur Nasional';
                        $cutiBersama = str_contains(mb_strtolower($desc), 'cuti bersama');
                        $events[] = [
                            'title' => $desc,
                            'start' => $h['date'] ?? null,
                            'allDay' => true,
                            'color' => $cutiBersama ? '#f76707' : '#d63939',
                            'extendedProps' => ['libur' => true],
                        ];
                    }
                } catch (\Throwable $e) {
                    // Abaikan: tampilkan kalender tanpa libur nasional bila API tak tersedia.
                }
            }

            return array_values(array_filter($events, fn ($e) => ! empty($e['start'])));
        });
    }

    /**
     * Agenda — daftar kegiatan mendatang.
     */
    public function agenda(): View
    {
        $kegiatan = Kegiatan::mendatang()->orderBy('mulai')->get();

        return view('frontend.agenda', ['kegiatan' => $kegiatan]);
    }
}
