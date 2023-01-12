<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Jobs\SyncCalendarEventsForUser;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Request $request)
    {
        $startOfWeek = now()->subDay(1)->startOfDay()->toImmutable();

        // @todo, throttle this so it doesn't run more than once an hour or something like that
        $this->dispatch(new SyncCalendarEventsForUser($request->user(), $startOfWeek, $startOfWeek->addDay(7)));

        return view('dashboard.show', [
            'from' => $startOfWeek,
            'to' => $startOfWeek->addDay(7),
            'days' => [
                $startOfWeek->addDay(0),
                $startOfWeek->addDay(1),
                $startOfWeek->addDay(2),
                $startOfWeek->addDay(3),
                $startOfWeek->addDay(4),
                $startOfWeek->addDay(5),
                $startOfWeek->addDay(6),
            ],
        ]);
    }
}
