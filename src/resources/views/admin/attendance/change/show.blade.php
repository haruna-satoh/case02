@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/attendance/change/show.css') }}">
@endsection

@section('content')
    <div class="attendance-detail">
        <div class="attendance-detail__container">
            <h2 class="attendance-detail__title">勤怠詳細</h2>

            <form action="{{ route('admin.change_request.approve', $changeRequest->id) }}" method="post">
                @csrf
                @method('patch')

                <div class="attendance-detail__card">
                    <table class="attendance-detail__table">
                        <tr>
                            <th>名前</th>
                            <td>{{ $changeRequest->user->name }}</td>
                        </tr>

                        <tr>
                            <th>日付</th>
                            <td>
                                {{ \Carbon\Carbon::parse($changeRequest->attendance->date)->format('Y年n月j日') }}
                            </td>
                        </tr>

                        <tr>
                            <th>出勤・退勤</th>
                            <td>
                            <input type="text" name="start_time" class="detail-input" value="{{ old('start_time', \Carbon\Carbon::parse($changeRequest->start_time)->format('H:i')) }}">

                            @error('start_time')
                                <p class="error">{{ $message }} </p>
                            @enderror

                            ~
                            <input type="text" name="end_time" class="detail-input" value="{{ old('end_time', \Carbon\Carbon::parse($changeRequest->end_time)->format('H:i')) }}">

                            @error('end_time')
                                <p class="error">{{ $message }} </p>
                            @enderror
                        </td>
                        </tr>

                        @foreach ($changeRequest->breakChanges as $index => $break)
                        <tr>
                            <th>休憩{{ $index + 1 }}</th>
                            <td>
                                <input type="hidden" name="breaks[{{ $index }}][break_number]" value="{{ $index + 1 }}">

                                <input type="time" name="breaks[{{ $index }}][start_time]" class="detail-input" value="{{ optional($break)->start_time ? \Carbon\Carbon::parse($break->start_time)->format('H:i') : '' }}">

                                @error("breaks.$index.start_time")
                                    <p class="error">{{ $message }}</p>
                                @enderror
                                ~
                                <input type="time" name="breaks[{{ $index }}][end_time]" class="detail-input" value="{{ optional($break)->end_time ? \Carbon\Carbon::parse($break->end_time)->format('H:i') : '' }}">

                                @error("breaks.$index.end_time")
                                    <p class="error">{{ $message }}</p>
                                @enderror
                            </td>
                        </tr>
                        @endforeach

                        <tr>
                            <th>備考</th>
                            <td>
                            <textarea name="note" id="" cols="30" rows="10" class="detail-textarea">{{ old('note', $changeRequest->note ?? '') }}</textarea>

                            @error('note')
                                <p class="error">{{ $message }} </p>
                            @enderror
                        </td>
                        </tr>
                    </table>
                </div>

                <div class="attendance-detail__button">
                    @if ($changeRequest->status === '承認待ち')
                        <button class="btn-approve" type="submit">承認</button>
                    @else
                        <p class="attendance-detail__approved">承認済み</p>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection