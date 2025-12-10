@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/attendance/show.css') }}">
@endsection

@section('content')
    <div class="attendance-detail">
        <h2 class="attendance-detail__title">勤怠詳細</h2>

        <div class="attendance-detail__table">
            <form action="{{ route('admin.attendance.update',$attendance->id) }}" method="post">
                @csrf
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
                            <input type="text" name="start_time" class="detail-input" value="{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}">
                            ~
                            <input type="text" name="end_time" class="detail-input" value="{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}">
                        </td>
                    </tr>
                    <tr>
                        <th>休憩</th>
                        <td>
                            @if (isset($attendance->breakTimes[0]))
                                <input type="text" name="break_start" class="detail-input" value="{{ \Carbon\Carbon::parse($attendance->breakTimes[0]->start_time)->format('H:i') }}">
                                ~
                                <input type="text" name="break_end" class="detail-input" value="{{ \Carbon\Carbon::parse($attendance->breakTimes[0]->end_time)->format('H:i') }}">
                            @else
                                --
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>休憩２</th>
                        <td>
                            @if (isset($attendance->breakTimes[1]))
                                <input type="text" name="break_start" class="detail-input" value="{{ \Carbon\Carbon::parse($attendance->breakTimes[1]->start_time)->format('H:i') }}"> ~
                                <input type="text" name="break_end" class="detail-input" value="{{ \Carbon\Carbon::parse($attendance->breakTimes[1]->end_time)->format('H:i') }}">
                            @else
                                --
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>備考</th>
                        <td>
                            <textarea name="note" id="" cols="30" rows="10" class="detail-textarea">{{ $attendance->note }}</textarea>
                        </td>
                    </tr>
                </table>

                <div class="attendance-detail__button-area">
                    @if ($attendance->status === '承認待ち')
                        <p class="error">承認待ちのため修正はできません。</p>
                    @else
                        <button type="submit"   class="attendance-detail__edit-button">
                            修正
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection