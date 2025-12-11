<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceBreakChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_change_request_id',
        'break_number',
        'start_time',
        'end_time',
    ];

    public function changeRequest() {
        return $this->belongsTo(AttendanceChangeRequest::class);
    }
}
