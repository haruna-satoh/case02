# プロジェクト名

coachtech勤怠管理アプリ

# 概要

勤怠を登録・管理することができるアプリです。
一般ユーザーは出勤・退勤などの勤怠情報を登録でき、管理者はユーザーの勤怠情報を一覧で確認できます。

## 環境

- php バージョン: 8.2
- Laravel バージョン: 8.83
- データベース: MySQL(Docker使用)

## セットアップ

1. このリポジトリをクローン
```bash
git clone git@github.com:haruna-satoh/case02.git
cd case02
```

2. Dockerを起動
``` bash
docker compose up -d --build
```

3. .envファイルを作成
``` bash
cp src/.env.example src/.env
```

4. Laravelアプリケーションのセットアップ
php コンテナ内で実行
```bash
docker compose exec php bash
composer install
php artisan key:generate
php artisan migrate --seed
```

## ER図
アプリ内で使用しているテーブル構成を示したER図です。

![ER図](勤怠管理.drawio.svg)

## テスト手順
MySQL コンテナ内で実行
```bash
docker compose exec mysql bash
mysql -u root -p
root
CREATE DATABASE demo_test;
exit;
exit
```
※ パスワードは.env.testingに記載しているものを使用してください

php コンテナ内で実行
```bash
docker compose exec php bash
php artisan migrate --env=testing
php artisan test
```
テスト結果が全ての項目でPASSであれば、基本動作が正常であると確認できます。

## 管理者アカウントについて

本アプリには管理者用ログインページがあります。

管理者アカウントの登録画面は用意していないため、管理者アカウントはこちらを使用してください
- ・ メールアドレス：admin@example.com
- ・ パスワード：password123

※ 本アプリは評価・学習目的のため、開発環境に管理者初期データを投入しています。
※ 本番環境では管理者情報をSeederから削除し、Tinkerで手動作成してください。

## URL

- [http://localhost/register](http://localhost/register)
    →一般ユーザーの会員登録画面が表示されます。
- [http://localhost/admin/login](http://localhost/admin/login)
    →管理者用のログイン画面が表示されます。
- [http://localhost:8080/](http://localhost:8080/)
    →phpMyAdminが表示され、DBを確認できます。

