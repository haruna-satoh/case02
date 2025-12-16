@extends('layouts.app')

@section('css')
    
@endsection

@section('content')
    <div class="attendance">
        <h2>勤怠登録</h2>

        <p>{{ now()->format('Y年m月d日') }}</p>
        <p>{{ now()->format('H:i') }}</p>

        @if (!$attendance)
            <button>出勤</button>
        @elseif ($attendance && !$attendance->end_time)
            <button>退勤</button>
            <button>休憩入</button>
        @else
            <p>お疲れ様でした。</p>
        @endif
    </div>
@endsection