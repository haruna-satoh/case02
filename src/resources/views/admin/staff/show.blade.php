@extends('layouts.app')

@section('content')
<div class="attendance-list">
    <h2 class="attendance-list__title">
        {{ $user->name }}さんの勤怠
    </h2>

    <table>
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
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('Y/m/d') }}</td>
                    <td>{{ $attendance->start_time }}</td>
                    <td>{{ $attendance->end_time }}</td>
                    <td>{{ $attendance->break_time }}</td>
                    <td>{{ $attendance->total_time }}</td>
                    <td>
                        <a href="{{ route('admin.attendance.show', $attendance->id) }}">
                            詳細
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection