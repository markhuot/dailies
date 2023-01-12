<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        // Validate the request
        $attributes = $request->validate([
            'user.name' => ['required', 'max:255'],
            'user.email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'user.password' => ['required', 'min:5', 'max:255'],
        ]);

        // Create the user
        $user = User::create($attributes['user']);

        // Sign the user in
        auth()->login($user);

        // Redirect the user
        return redirect('/')->with('success', 'Your account has been created.');
    }
}
