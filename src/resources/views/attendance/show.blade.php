@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance/show.css') }}">
@endsection

@section('content')
    <div class="attendance-detail">
        <h1 class="attendance-detail__title">勤怠詳細</h1>

        <div class="attendance-detail__table">
            <form action="{{ route('attendance.change.store.user',$attendance->id) }}" method="post">
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
                            <input type="text" name="start_time" class="detail-input" value="{{ old('start_time', \Carbon\Carbon::parse($attendance->start_time)->format('H:i')) }}">

                            @error('start_time')
                                <p class="error">{{ $message }} </p>
                            @enderror

                            ~
                            <input type="text" name="end_time" class="detail-input" value="{{ old('end_time', \Carbon\Carbon::parse($attendance->end_time)->format('H:i')) }}">

                            @error('end_time')
                                <p class="error">{{ $message }} </p>
                            @enderror
                        </td>
                    </tr>
                    @php
                        $breaks = $attendance->breakTimes;
                        $count = count($breaks);
                    @endphp

                    @for ($i = 0; $i < $count; $i++)
                        @php
                            $b = $breaks[$i];
                        @endphp
                        <tr>
                            <th>休憩{{ $i + 1 }}</th>
                            <td>
                                <input type="hidden" name="breaks[{{ $i }}][break_number]" value="{{ $i + 1 }}">

                                <input type="time" name="breaks[{{ $i }}][start_time]" class="detail-input" value="{{ optional($b)->start_time ? \Carbon\Carbon::parse($b->start_time)->format('H:i') : '' }}">

                                @error("breaks.$i.start_time")
                                    <p class="error">{{ $message }}</p>
                                @enderror
                                ~
                                <input type="time" name="breaks[{{ $i }}][end_time]" class="detail-input" value="{{ optional($b)->end_time ? \Carbon\Carbon::parse($b->end_time)->format('H:i') : '' }}">

                                @error("breaks.$i.end_time")
                                    <p class="error">{{ $message }}</p>
                                @enderror
                            </td>
                        </tr>
                    @endfor
                    <tr>
                        <th>休憩{{ $count + 1 }}</th>
                        <td>
                            <input type="hidden" name="breaks[{{ $count }}][break_number]" value="{{ $count + 1 }}">

                            <input type="time" name="breaks[{{ $count }}][start_time]" class="detail-input" value="">
                            ~
                            <input type="time" name="breaks[{{ $count }}][end_time]" class="detail-input" value="">
                        </td>
                    </tr>
                    <tr>
                        <th>備考</th>
                        <td>
                            <textarea name="note" id="" cols="30" rows="10" class="detail-textarea">{{ old('note', $attendance->changeRequest->note ?? '') }}</textarea>

                            @error('note')
                                <p class="error">{{ $message }} </p>
                            @enderror
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