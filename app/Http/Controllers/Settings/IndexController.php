<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Jobs\SyncCalendarsForUser;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    function __invoke(Request $request)
    {
        // @todo, only update once per day...
        $this->dispatch(new SyncCalendarsForUser($request->user()));

        return view('settings.index');
    }
}
