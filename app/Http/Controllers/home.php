<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\ImportantDate;
use App\Models\Speaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class home extends Controller
{
    /**
     * Display the home page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $conference = Conference::where('is_active', true)
            ->whereHas('schedules', fn($q) => $q->where('start_time', '>=', now()))
            ->with([
                'schedules' => fn($q) => $q->where('start_time', '>=', now())->orderBy('start_time'),
                'venues',
                'seminarFees',
                'sponsors',
            ])
            ->get()
            ->sortBy(fn($conf) => optional($conf->schedules->first())->start_time)
            ->first();

        if (!$conference) {
            $conference = Conference::where('is_active', true)
                ->whereHas('schedules')
                ->with([
                    'schedules' => fn($q) => $q->orderBy('start_time', 'desc'),
                    'venues',
                    'seminarFees',
                    'sponsors',
                ])
                ->get()
                ->sortByDesc(fn($conf) => optional($conf->schedules->first())->start_time)
                ->first();
        }

        $conferences = $conference ? [$conference] : [];

        $countdownTime = $conference && $conference->schedules->first()
            ? $conference->schedules->first()->start_time
            : null;

        $speakers = Speaker::all();
        $onlineFees = $conference?->seminarFees->where('type', 'online') ?? collect();
        $offlineFees = $conference?->seminarFees->where('type', 'offline') ?? collect();

        $venue = $conference?->venues->first(); // hanya ambil satu venue utama untuk tampil
        $venueQuery = $venue ? urlencode($venue->name . ', ' . $venue->address) : null;

        $sponsors = $conference?->sponsors ?? collect();

        $importantDates = $conference
            ? ImportantDate::where('conference_id', $conference->id)->orderBy('date')->get()
            : collect();

        return view('index', compact(
            'conference',
            'conferences',
            'countdownTime',
            'speakers',
            'onlineFees',
            'offlineFees',
            'venue',
            'sponsors',
            'importantDates'
        ));
    }
}
