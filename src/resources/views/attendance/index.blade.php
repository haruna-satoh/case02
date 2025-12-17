@extends('layouts.app')

@section('css')
    
@endsection

@section('content')
    <div class="attendance">
        <p class="attendance-status">
            @if (!$attendance)
                勤務外
            @elseif ($attendance->status === '出勤中')
                出勤中
            @elseif ($attendance->status === '休憩中')
                休憩中
            @elseif ($attendance->status === '退勤済')
                退勤済
            @endif
        </p>

        <p>{{ now()->format('Y年m月d日') }}</p>
        <p>{{ now()->format('H:i') }}</p>

        @if (!$attendance)
            <form action="{{ route('attendance.start') }}" method="post">
                @csrf
                <button type="submit">出勤</button>
            </form>
        @elseif ($attendance->status === '休憩中')
            <form action="{{ route('attendance.break.end') }}" method="post">
                @csrf
                <button type="submit">休憩戻</button>
            </form>
        @elseif ($attendance && !$attendance->end_time)
            <form action="{{ route('attendance.end') }}" method="post">
                @csrf
                <button type="submit">退勤</button>
            </form>

            <form action="{{ route('attendance.break.start') }}" method="post">
                @csrf
                <button type="submit">休憩入</button>
            </form>
        @else
            <p>お疲れ様でした。</p>
        @endif
    </div>
@endsection