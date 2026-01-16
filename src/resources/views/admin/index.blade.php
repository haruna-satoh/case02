@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/index.css') }}">
@endsection

@section('content')
    <div class="attendance">
        <div class="attendance__header">
            <h1 class="attendance__title">
                {{ $date }}の勤怠
            </h1>
            <div class="attendance__date-nav--cade">
                <div class="attendance__date-nav">
                    <form action="{{ route('admin.attendance.index') }}" method="get" class="attendance__date-nav">
                        @csrf
                        <button class="attendance__button" type="submit" name="nav" value="{{ \Carbon\Carbon::parse($date)->subDay()->toDateString() }}">
                            ← 前日
                        </button>
                        <div class="attendance__date-input">
                            <input type="date" name="selected_date" value="{{ $date }}">
                        </div>
                        <button class="attendance__button" type="submit" name="nav" value="{{ \Carbon\Carbon::parse($date)->addDay()->toDateString() }}">
                            翌日 →
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="attendance__table--cade">
            <table class="attendance__table">
                <tr>
                    <th>名前</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>
                @foreach($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}</td>
                        <td>{{ $attendance->formatted_break_time }}</td>
                        <td>{{ $attendance->formatted_work_minutes }}</td>
                        <td>
                            <a href="{{ route('admin.attendance.show', $attendance->id) }}">詳細</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection