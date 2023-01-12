<?php

namespace App\Jobs;

use App\Models\Calendar;
use App\Models\OauthToken;
use App\Models\User;
use Google\Client;
use Google\Service\Calendar\CalendarListEntry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncCalendarsForUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected User $user,
    ) {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->oauthTokens()
            ->where('service', '=', 'google')
            ->get()
            ->each(fn ($token) => $this->syncForToken($token));
    }

    protected function syncForToken(OauthToken $token)
    {
        $client = new Client;
        $client->setAccessToken($token->access_token);
        $calendarService = new \Google\Service\Calendar($client);

        $calendarList = $calendarService->calendarList->listCalendarList();
        /** @var CalendarListEntry $googleCalendar */
        foreach ($calendarList as $googleCalendar) {
            Calendar::updateOrCreate(
                ['remote_service' => 'google', 'remote_id' => $googleCalendar->id],
                ['oauth_token_id' => $token->id, 'user_id' => $this->user->id, 'name' => $googleCalendar->summary, 'raw' => json_encode($googleCalendar->toSimpleObject())],
            );
        }
    }
}
