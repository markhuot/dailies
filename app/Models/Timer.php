<?php

namespace App\Models;

use App\Collections\TimerCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timer extends Model
{
    use HasFactory;

    protected $casts = [
        'started_at' => 'datetime',
        'stopped_at' => 'datetime',
    ];

    public function newCollection(array $models = [])
    {

        return new TimerCollection($models);
    }

    public function duration()
    {
        return $this->started_at->diffAsCarbonInterval($this->stopped_at ?? now());
    }
}
