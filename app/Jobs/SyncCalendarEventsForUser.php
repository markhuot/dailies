<?php

namespace App\Jobs;

use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Models\Task;
use App\Models\User;
use Carbon\CarbonImmutable;
use Google\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncCalendarEventsForUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected User $user,
        protected CarbonImmutable $from,
        protected CarbonImmutable $until,
    ) {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Client $client)
    {
        $this->user->calendars()
            ->where('remote_service', '=', 'google')
            ->get()
            ->filter(fn ($calendar) => $this->user->isSyncingCalendar($calendar->id))
            ->each(fn ($calendar) => $this->syncCalendar($calendar));
    }

    protected function syncCalendar(Calendar $calendar)
    {
        $client = new Client;
        $client->setAccessToken($calendar->oauthToken->access_token);

        // @todo abstract this!
        if ($client->isAccessTokenExpired()) {
            $newAccessToken = app(Client::class)->fetchAccessTokenWithRefreshToken($calendar->oauthToken->refresh_token);

            $calendar->oauthToken->access_token = $newAccessToken['access_token'];
            $calendar->oauthToken->refresh_token = $newAccessToken['refresh_token'];
            $calendar->oauthToken->expires_at = now()->addSeconds($newAccessToken['expires_in'])->subSeconds(60);
            $calendar->oauthToken->raw = json_encode($newAccessToken, JSON_THROW_ON_ERROR);
            $calendar->oauthToken->save();
            $client->setAccessToken($newAccessToken['access_token']);
        }

        $calendarService = new \Google\Service\Calendar($client);

        $id = $calendar->remote_id;
        $events = $calendarService->events->listEvents($id, [
            'timeMin' => $this->from->toAtomString(),
            'timeMax' => $this->until->toAtomString(),
        ])->items;

        /** @var \Google\Service\Calendar\Event $event */
        foreach ($events as $event)
        {
            $task = Task::updateOrCreate(
                ['remote_service' => 'google', 'remote_id' => $event->id],
                [
                    'name' => $event->summary,
                    'date' => CarbonImmutable::create($event->start->dateTime),
                    'starts_at' => $event->start->dateTime,
                    'ends_at' => $event->end->dateTime,
                    'raw' => json_encode($event->toSimpleObject())
                ],
            );

            $this->user->tasks()->detach($task);
            $this->user->tasks()->attach($task);
        }
    }
}
