<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\View\View;

class KalenderController extends Controller
{
    /**
     * Kalender Akademik — tampilan kalender (FullCalendar).
     */
    public function index(): View
    {
        $events = Kegiatan::orderBy('mulai')->get()->map->toEvent()->values();

        return view('frontend.kalender', ['events' => $events]);
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
