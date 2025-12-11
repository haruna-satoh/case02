<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'start_time',
        'end_time',
        'note',
        'status'
    ];

    public function breakChanges() {
        return $this->hasMany(AttendanceBreakChangeRequest::class);
    }

    public function attendance() {
        return $this->belongsTo(Attendance::class);
    }
}
