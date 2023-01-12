<?php

namespace App\Http\Controllers\Oauth\GoogleCalendar;

use App\Http\Controllers\Controller;
use App\Models\OauthToken;
use Google\Client;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    function __invoke(Client $client, Request $request)
    {
        $accessToken = $client->fetchAccessTokenWithAuthCode($request->get('code'));

        $token = new OauthToken;
        $token->service = 'google';
        $token->access_token = $accessToken['access_token'];
        $token->refresh_token = $accessToken['refresh_token'];
        $token->expires_at = now()->addSeconds($accessToken['expires_in'])->subSeconds(60);
        $token->raw = json_encode($accessToken);

        $request->user()->oauthTokens()->save($token);

        return redirect()->route('settings.index');
    }
}
