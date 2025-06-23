<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\Speaker;
use Illuminate\Http\Request;

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
                'schedules' => fn($q) => $q->where('start_time', '>=', now())->orderBy('start_time', 'asc'),
                'seminarFees',
            ])
            ->get()
            ->sortBy(fn($conf) => optional($conf->schedules->first())->start_time)
            ->first();

        if (!$conference) {
            $conference = Conference::where('is_active', true)
                ->whereHas('schedules')
                ->with([
                    'schedules' => fn($q) => $q->orderBy('start_time', 'desc'),
                    'seminarFees',
                    'venues',
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

        // pisahkan biaya nasional dan internasional
        $nationalFees = $conference?->seminarFees->where('type', 'national') ?? collect();
        $internationalFees = $conference?->seminarFees->where('type', 'international') ?? collect();

        return view('index', compact(
            'conference',
            'conferences',
            'countdownTime',
            'speakers',
            'nationalFees',
            'internationalFees',
        ));
    }
}