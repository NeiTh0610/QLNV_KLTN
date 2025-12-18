<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_date',
        'check_in_at',
        'check_in_method',
        'check_in_ip',
        'check_in_wifi_id',
        'check_out_at',
        'check_out_method',
        'check_out_ip',
        'check_out_wifi_id',
        'status',
        'note',
    ];

    protected $casts = [
        'work_date' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getWorkedMinutesAttribute(): ?int
    {
        if (!$this->check_in_at || !$this->check_out_at) {
            return null;
        }

        return $this->check_in_at->diffInMinutes($this->check_out_at);
    }

    public function getWorkedDurationTextAttribute(): ?string
    {
        $minutes = $this->worked_minutes;

        if ($minutes === null) {
            return null;
        }

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        return "{$hours}h {$remainingMinutes}m";
    }
}

