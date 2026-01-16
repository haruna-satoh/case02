@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/attendance/change/index.css') }}">
@endsection

@section('content')
<div class="request-list">
    <h1 class="request-list__title">申請一覧</h1>

    <div class="request-list__tab">
        <a href="{{ route('admin.attendance.change.index') }}" class="attendance-detail__tab-button {{ $tab === 'pending' ? 'is-active' : '' }}">承認待ち</a>

        <a href="{{ route('admin.attendance.change.index', ['tab' => 'approved']) }}" class="attendance-detail__tab-button {{ $tab === 'approved' ? 'is-active' : '' }}">承認済み</a>
    </div>

    <div class="request-list__table-wrap">
        <table>
            <thead>
                <tr>
                    <th>状態</th>
                    <th>名前</th>
                    <th>対象日時</th>
                    <th>申請理由</th>
                    <th>申請日時</th>
                    <th>詳細</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($changeRequests as $request)
                    <tr>
                        <td>{{ $request->status }}</td>
                        <td>{{ $request->user?->name ?? '不明' }}</td>
                        <td>{{ \Carbon\Carbon::parse($request->attendance->date)->format('Y/m/d') }}</td>
                        <td>{{ $request->note }}</td>
                        <td>{{ \Carbon\Carbon::parse($request->created_at)->format('Y/m/d') }}</td>
                        <td>
                            <a href="{{ route('admin.change_request.show', $request->id) }}">
                                詳細
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection