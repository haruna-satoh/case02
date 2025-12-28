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
                                {{ \Carbon\Carbon::parse($changeRequest->start_time)->format('H:i') }}
                                ~
                                {{ \Carbon\Carbon::parse($changeRequest->end_time)->format('H:i') }}
                            </td>
                        </tr>

                        @foreach ($changeRequest->breakChanges as $index => $break)
                            <tr>
                                <th>休憩{{ $index + 1 }}</th>
                                <td>
                                    {{ \Carbon\Carbon::parse($break->start_time)->format('H:i') }}
                                    ~
                                    {{ \Carbon\Carbon::parse($break->end_time)->format('H:i') }}
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <th>備考</th>
                            <td>{{ $changeRequest->note }}</td>
                        </tr>
                    </table>
                </div>

                <div class="attendance-detail__button">
                    <button class="btn-approve" type="submit">承認</button>
                </div>
            </form>
        </div>
    </div>
@endsection