@extends('layouts.app')

@section('css')
    
@endsection

@section('content')
    <div class="attendance">
        <h2>勤怠登録</h2>

        <p>{{ now()->format('Y年m月d日') }}</p>
        <p>{{ now()->format('H:i') }}</p>

        @if (!$attendance)
            <form action="{{ route('attendance.start') }}" method="post">
                @csrf
                <button type="submit">出勤</button>
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