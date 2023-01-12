<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(Request $request)
    {
        $attributes = $request->validate([
            'settings.sync_calendars' => [],
        ]);

        $request->user()->update($attributes);
        $request->user()->save();

        return redirect()->route('settings.index');
    }
}
