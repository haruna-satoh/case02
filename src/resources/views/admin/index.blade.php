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
            <div class="attendance__date-nav--cade">
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
                <tr>
                    <td>山田太郎</td>
                    <td>09:00</td>
                    <td>18:00</td>
                    <td>1:00</td>
                    <td>8:00</td>
                    <td><a href="/admin/attendance/{id}">詳細</a></td>
                </tr>
                <tr>
                    <td>西伶奈</td>
                    <td>09:00</td>
                    <td>18:00</td>
                    <td>1:00</td>
                    <td>8:00</td>
                    <td><a href="/admin/attendance/{id}">詳細</a></td>
                </tr>
                <tr>
                    <td>増田一世</td>
                    <td>09:00</td>
                    <td>18:00</td>
                    <td>1:00</td>
                    <td>8:00</td>
                    <td><a href="/admin/attendance/{id}">詳細</a></td>
                </tr>
                <tr>
                    <td>山本敬吉</td>
                    <td>09:00</td>
                    <td>18:00</td>
                    <td>1:00</td>
                    <td>8:00</td>
                    <td><a href="/admin/attendance/{id}">詳細</a></td>
                </tr>
                <tr>
                    <td>秋田朋美</td>
                    <td>09:00</td>
                    <td>18:00</td>
                    <td>1:00</td>
                    <td>8:00</td>
                    <td><a href="/admin/attendance/{id}">詳細</a></td>
                </tr>
                <tr>
                    <td>中西教夫</td>
                    <td>09:00</td>
                    <td>18:00</td>
                    <td>1:00</td>
                    <td>8:00</td>
                    <td><a href="/admin/attendance/{id}">詳細</a></td>
                </tr>
            </table>
        </div>
    </div>
@endsection