@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance/index.css') }}">
@endsection

@section('content')
    <div class="attendance">
        <p class="attendance-status">
            @if (!$attendance)
                勤務外
            @elseif ($attendance->status === '休憩中')
                休憩中
            @elseif ($attendance && !$attendance->end_time)
                出勤中
            @else
                退勤済
            @endif
        </p>

        <p class="attendance-date">{{ now()->isoFormat('Y年M月D日(ddd)') }}</p>
        <p class="attendance-time">{{ now()->format('H:i') }}</p>

        <div class="attendance-buttons">
            @if (!$attendance)
                <form action="{{ route('attendance.start') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-primary">出勤</button>
                </form>
            @elseif ($attendance->status === '休憩中')
                <form action="{{ route('attendance.break.end') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-secondary">休憩戻</button>
                </form>
            @elseif ($attendance && !$attendance->end_time)
                <form action="{{ route('attendance.end') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-primary">退勤</button>
                </form>

                <form action="{{ route('attendance.break.start') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-secondary">休憩入</button>
                </form>
            @else
                <p class="attendance-finish">お疲れ様でした。</p>
            @endif
        </div>
    </div>
@endsection