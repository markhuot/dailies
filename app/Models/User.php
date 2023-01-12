<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'settings' => 'json',
    ];

    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }

    public function oauthTokens()
    {
        return $this->hasMany(OauthToken::class);
    }

    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }

    public function isSyncingCalendar(int $calendarId): bool
    {
        return in_array($calendarId, $this->settings['sync_calendars'] ?? [], false);
    }

    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class);
    }

    public function tasksAndEvents(CarbonImmutable $from, CarbonImmutable $to)
    {
        $tasks = $this->tasks()
            ->where('date', '>=', $from)
            ->where('date', '<=', $to)
            ->orderBy('date')
            ->get();

        $events = $this->calendarEvents()
            ->where('ends_at', '>=', $from)
            ->where('starts_at', '<=', $to)
            ->orderBy('starts_at')
            ->get();

        return $tasks->concat($events);
    }
}
