<?php

namespace App\Http\Controllers;

use App\Models\Conference;
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
        // Ambil conference aktif yang memiliki jadwal mendatang
        $conference = Conference::where('is_active', true)
            ->whereHas('schedules', function ($query) {
                $query->where('start_time', '>=', now());
            })
            ->with(['schedules' => function ($query) {
                $query->where('start_time', '>=', now())
                    ->orderBy('start_time', 'asc');
            }])
            ->get()
            ->sortBy(function ($conf) {
                return optional($conf->schedules->first())->start_time;
            })
            ->first();

        // Jika tidak ada conference mendatang, ambil conference terakhir (meskipun sudah lewat)
        if (!$conference) {
            $conference = Conference::where('is_active', true)
                ->whereHas('schedules')
                ->with(['schedules' => function ($query) {
                    $query->orderBy('start_time', 'desc');
                }])
                ->get()
                ->sortByDesc(function ($conf) {
                    return optional($conf->schedules->first())->start_time;
                })
                ->first();
        }

        $conferences = $conference ? [$conference] : [];

        // Ambil waktu countdown dari jadwal terdekat (jika ada)
        $countdownTime = $conference && $conference->schedules->first()
            ? $conference->schedules->first()->start_time
            : null;

        return view('index', compact('conferences', 'countdownTime'));
    }
}
