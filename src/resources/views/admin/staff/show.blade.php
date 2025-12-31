@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/staff/show.css') }}">
@endsection

@section('content')
<div class="attendance-list">
    <div class="attendance-list__header">
        <h2 class="attendance-list__title">
            {{ $user->name }}さんの勤怠
        </h2>

        <div class="month__nav--cade">
            <div class="month__nav">
                <a class="month__prev" href="{{ route('admin.staff.show', ['id' => $user->id, 'month' => $prevMonth]) }}">
                    ← 前月
                </a>

                <span class="month__current">
                    {{ $month->format('Y/m') }}
                </span>

                <a class="month__next" href="{{ route('admin.staff.show', ['id' => $user->id, 'month' => $nextMonth]) }}">
                    翌月 →
                </a>
            </div>
        </div>
    </div>

    <table class="attendance__table">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dates as $date)
                @php
                    $attendance = $attendances[$date->format('Y-m-d')] ?? null;
                @endphp
                <tr>
                    <td>{{ $date->isoFormat('MM/DD(ddd)') }}</td>
                    <td>{{ $attendance && $attendance->start_time ? \Carbon\Carbon::parse($attendance->start_time)->format('H:i') : '' }}</td>
                    <td>{{ $attendance && $attendance->end_time ? \Carbon\Carbon::parse($attendance->end_time)->format('H:i') : '' }}</td>
                    <td>{{ $attendance ? $attendance->formatted_break_time : '' }}</td>
                    <td>{{ $attendance ? $attendance->formatted_work_minutes : '' }}</td>
                    <td>
                        @if ($attendance)
                            <a href="{{ route('admin.attendance.show', $attendance->id) }}">
                                詳細
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.staff.csv', ['id' => $user->id, 'month' => $month->format('Y-m')]) }}">
        CSV出力
    </a>
</div>
@endsection