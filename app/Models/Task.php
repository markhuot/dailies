<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property CarbonImmutable $date
 */
class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'complete' => 'boolean',
    ];

    public function timers()
    {
        return $this->hasMany(Timer::class);
    }

    public function getLivewireKey()
    {
        return implode('.', array_filter([
            $this->id,
            $this->updated_at->format('YmdHis'),
        ]));
    }

    public function note()
    {
        return $this->hasOne(Note::class);
    }

    public function occursOn(CarbonImmutable $date): bool
    {
        return $date->isSameDay($this->date);
    }
}
