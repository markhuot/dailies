<?php

namespace App\Http\Controllers\Oauth\GoogleCalendar;

use App\Http\Controllers\Controller;
use Google\Client;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    function __invoke(Client $client)
    {
        $authUrl = $client->createAuthUrl();

        return redirect()->to($authUrl);
    }
}
