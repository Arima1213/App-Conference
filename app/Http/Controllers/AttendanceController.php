<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AttendanceController extends Controller
{
    public function showParticipantQr($encrypted)
    {
        $id = Crypt::decryptString($encrypted);
        $participant = \App\Models\Participant::findOrFail($id);

        return view('participant.qr-show', compact('participant'));
    }
}