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
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('Y年m月d日') }}</td>
                </tr>
                <tr>
                    <th>出勤・退勤</th>
                    <td>
                        <input type="text" class="detail-input" value="{{ $attendance->start_time }}">
                        ~
                        <input type="text" class="detail-input" value="{{ $attendance->end_time }}">
                    </td>
                </tr>
                <tr>
                    <th>休憩</th>
                    <td>
                        @if (isset($attendance->breakTimes[0]))
                            <input type="text" class="detail-input" value="{{ $attendance->breakTimes[0]->start_time }}">
                            ~
                            <input type="text" class="detail-input" value="{{ $attendance->breakTimes[0]->end_time }}">
                        @else
                            --
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>休憩２</th>
                    <td>
                        @if (isset($attendance->breakTimes[1]))
                            <input type="text" class="detail-input" value="{{ $attendance->breakTimes[1]->start_time }}"> ~
                            <input type="text" class="detail-input" value="{{ $attendance->breakTimes[1]->end_time }}">
                        @else
                            --
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>備考</th>
                    <td>
                        <textarea name="" id="" cols="30" rows="10" class="detail-textarea">{{ $attendance->note }}</textarea>
                    </td>
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