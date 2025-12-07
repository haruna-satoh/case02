@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/attendance/show.css') }}">
@endsection

@section('content')
    <div class="attendance-detail">
        <h2 class="attendance-detail__title">勤怠詳細</h2>

        <div class="attendance-detail__table">
            <table>
                <tr>
                    <th>名前</th>
                    <td>{{ $attendance->user->name }}</td>
                </tr>
                <tr>
                    <th>日付</th>
                    <td>{{ $attendance->date }}</td>
                </tr>
                <tr>
                    <th>出勤・退勤</th>
                    <td>{{ $attendance->start_time }} ~ {{ $attendance->end_time }}</td>
                </tr>
                <tr>
                    <th>休憩</th>
                    <td>
                        @if (isset($attendance->breakTimes[0]))
                            {{ $attendance->breakTimes[0]->start_time }} ~ {{ $attendance->breakTimes[0]->end_time }}
                        @else
                            --
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>休憩２</th>
                    <td>
                        @if (isset($attendance->breakTimes[1]))
                            {{ $attendance->breakTimes[1]->start_time }} ~ {{ $attendance->breakTimes[1]->end_time }}
                        @else
                            --
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>備考</th>
                    <td>{{ $attendance->note }}</td>
                </tr>
            </table>
        </div>

        <div class="attendance-detail__button-area">
            <a href="" class="attendance-detail__edit-button">
                修正
            </a>
        </div>
    </div>
@endsection