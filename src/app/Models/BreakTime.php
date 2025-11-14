<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BreakTime extends Model
{
    use HasFactory;
    protected $fillable = [
        'attendance_id',
        'start_time',
        'end_time',
        'break_time',
    ];

    public function attendance() {
        return $this->belongsTo(Attendance::class);
    }

    protected static function booted() {
        static::saving(function ($break) {
            if($break->start_time && $break->end_time){
                $start = Carbon::parse($break->start_time);
                $end = Carbon::parse($break->end_time);
                $break->break_time = $end->diffInMinutes($start);
            }
        });
    }
}
