<?php

namespace App\Http\Controllers\Session;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            'user.email' => ['required', 'email'],
            'user.password' => ['required'],
        ]);

        if (Auth::attempt($credentials['user'])) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard.show');
        }

        return back();
    }
}
