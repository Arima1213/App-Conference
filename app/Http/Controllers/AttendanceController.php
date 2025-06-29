<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function showParticipantQr($encrypted)
    {
        $id = Crypt::decryptString($encrypted);
        $participant = Participant::with(['user', 'conference'])->findOrFail($id);

        return view('validation', compact('participant'));
    }

    public function validateAttendance($id)
    {
        $participant = Participant::findOrFail($id);

        // Cek apakah user login dan berasal dari panel 'manage'
        if (!Auth::check() || filament()->getPanel()->getId() !== 'manage') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk memvalidasi.');
        }

        if ($participant->status !== 'arrived') {
            $participant->status = 'arrived';
            $participant->save();

            return redirect()->back()->with('success', 'Kehadiran peserta berhasil divalidasi.');
        }

        return redirect()->back()->with('success', 'Peserta sudah hadir sebelumnya.');
    }

    public function showSeminarKitQr($encrypted)
    {
        $id = Crypt::decryptString($encrypted);
        $participant = \App\Models\Participant::with('user', 'conference')->findOrFail($id);

        return view('participant.qr-show-seminar-kit', compact('participant'));
    }
}