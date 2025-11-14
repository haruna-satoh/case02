<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BreakTime;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'total_time',
    ];

    public function breakTimes() {
        return $this->hasMany(BreakTime::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    protected static function booted() {
        static::saving(function ($attendance) {
            if ($attendance->start_time && $attendance->end_time) {
                $start = Carbon::parse($attendance->start_time);
                $end = Carbon::parse($attendance->end_time);
                $attendance->total_time = $end->diffInMinutes($start);
            }
        });
    }

    public function getWorkMinutesAttribute() {
        $totalBreak = $this->breakTimes->sum('break_time');
        return $this->total_time - $totalBreak;
    }

    public function getFormattedTotalTimeAttribute() {
        $hours = floor($this->total_time / 60);
        $minutes = $this->total_time % 60;
        return sprintf('%d:%02d', $hours, $minuted);
    }
}
