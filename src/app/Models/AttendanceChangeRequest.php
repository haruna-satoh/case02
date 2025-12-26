<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_id',
        'start_time',
        'end_time',
        'note',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function breakChanges() {
        return $this->hasMany(AttendanceBreakChangeRequest::class);
    }

    public function attendance() {
        return $this->belongsTo(Attendance::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
