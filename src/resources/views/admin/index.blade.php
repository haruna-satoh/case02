@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/index.css') }}">
@endsection

@section('content')
    <div class="attendance">
        <div class="attendance__header">
            <h2 class="attendance__title">
                2023年6月1日の勤怠
            </h2>
            <div class="attendance__date-nav">
                <button class="attendance__button">
                    前日
                </button>
                <div class="attendance__date-input">
                    <input type="date" name="date" value="2023-06-01">
                </div>
                <button class="attendance__button">
                    翌日
                </button>
            </div>
        </div>
    </div>
@endsection