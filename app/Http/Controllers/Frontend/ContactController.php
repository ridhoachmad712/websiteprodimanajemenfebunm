<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request): RedirectResponse
    {
        $data = $request->safe()->only(['nama', 'email', 'subjek', 'pesan']);

        $message = ContactMessage::create($data);

        // Notifikasi ke email prodi (driver 'log' di dev → tercatat di log).
        $tujuan = Setting::get('kontak.email');
        if ($tujuan) {
            try {
                Mail::raw(
                    "Pesan baru dari {$message->nama} <{$message->email}>\n\n"
                    .($message->subjek ? "Subjek: {$message->subjek}\n\n" : '')
                    .$message->pesan,
                    function ($mail) use ($tujuan, $message) {
                        $mail->to($tujuan)
                             ->replyTo($message->email, $message->nama)
                             ->subject('[Kontak Web] '.($message->subjek ?: 'Pesan baru'));
                    }
                );
            } catch (\Throwable $e) {
                // Email gagal tidak boleh menggagalkan penyimpanan pesan.
                report($e);
            }
        }

        return back()->with('contact_success', 'Terima kasih! Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
    }
}
