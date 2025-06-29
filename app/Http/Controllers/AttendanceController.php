<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display the participant QR validation page.
     *
     * @param string $encrypted
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showParticipantQr($encrypted)
    {
        try {
            $id = Crypt::decryptString($encrypted);
            $participant = Participant::with(['user', 'conference'])->findOrFail($id);

            return view('validation', compact('participant'));
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return redirect()->route('home')->with('error', 'The QR code is invalid or has been corrupted.');
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'An error occurred while processing the QR code.');
        }
    }

    /**
     * Validate participant attendance.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateAttendance($id)
    {
        try {
            $participant = Participant::findOrFail($id);

            if (!Auth::check() || (function_exists('filament') && filament()->getPanel()->getId() !== 'manage')) {
                return redirect()->back()->with('error', 'You do not have permission to validate attendance.');
            }

            if ($participant->status === 'arrived') {
                return redirect()->back()->with('info', 'The participant has already been marked as present.');
            }

            $participant->status = 'arrived';
            $participant->save();

            return redirect()->back()->with('success', 'Participant attendance has been successfully validated.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Participant not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while validating attendance.');
        }
    }

    /**
     * Display the seminar kit QR validation page.
     *
     * @param string $encrypted
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showSeminarKitQr($encrypted)
    {
        try {
            $id = Crypt::decryptString($encrypted);
            $participant = Participant::with(['user', 'conference'])->findOrFail($id);

            return view('validationSeminarKit', compact('participant'));
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return redirect()->route('home')->with('error', 'The QR code is invalid or has been corrupted.');
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'An error occurred while processing the QR code.');
        }
    }

    /**
     * Validate seminar kit reception.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateSeminarKit($id)
    {
        try {
            $participant = Participant::findOrFail($id);

            if (!Auth::check() || (function_exists('filament') && filament()->getPanel()->getId() !== 'manage')) {
                return redirect()->back()->with('error', 'You do not have permission to validate the seminar kit.');
            }

            if ($participant->seminar_kit_status === 'received') {
                return redirect()->back()->with('info', 'The seminar kit has already been received.');
            }

            $participant->seminar_kit_status = 'received';
            $participant->save();

            return redirect()->back()->with('success', 'Participant seminar kit has been successfully validated.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Participant not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while validating the seminar kit.');
        }
    }
}