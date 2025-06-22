<?php

namespace App\Http\Controllers;

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
        $conference = \App\Models\Conference::where('is_active', true)
            ->whereHas('schedules', function ($query) {
                $query->where('start_time', '>=', now());
            })
            ->with(['schedules' => function ($query) {
                $query->orderBy('start_time', 'asc');
            }])
            ->orderByRaw('(select min(start_time) from schedules where schedules.conference_id = conferences.id) asc')
            ->first();
        $conferences = $conference ? [$conference] : [];

        dd($conferences);
        return view('index', ['conferences' => $conferences]);
    }
}
