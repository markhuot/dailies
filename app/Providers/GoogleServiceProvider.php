<?php

namespace App\Providers;

use Google\Client;
use Google\Service\Calendar;
use Illuminate\Support\ServiceProvider;

class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            $client = new Client();
            $client->setAuthConfig(storage_path('client_credentials.json'));
            $client->addScope(Calendar::CALENDAR_READONLY);
            $client->addScope(Calendar::CALENDAR_EVENTS_READONLY);
            $client->setRedirectUri(route('oauth.google-calendar.callback'));
            $client->setAccessType('offline');

            return $client;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
