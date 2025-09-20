# Rese (飲食店予約サービス)
- ユーザーはグループ会社が運営する飲食店情報を閲覧でき、更に会員登録すると予約（変更可）、お気に入り店舗登録や来店後の評価・コメントを送信できる
- 蓄積された評価・コメントは飲食店詳細画面で誰でも参照することができる
<img width="2227" height="1563" alt="image" src="https://github.com/user-attachments/assets/3c1f510a-127d-477a-b447-91955f1d2fe7" />

## 作成した目的 
- 外部の飲食店予約サービスは手数料を取られるので自社で予約サービスを持ちたい。

## アプリケーションURL
- 飲食店一覧画面 : http://localhost/
- 会員登録画面 : http://localhost/register
- phpMyAdmin : http://localhost:8080
- ログイン画面：http://localhost/login
  - シーダー実行にて初期ユーザー作成されます。ログイン情報（メアド / パスワード)です。
    - test1@test.com / test1pass   // 通常ユーザー(顧客相当)
    - test2@test.com / test2pass   // 同上
    - test3@test.com / test3pass   // 同上
    - test4@test.com / test4pass   // 管理者(Admin)
      - ログイン画面は同じで、ログイン後メニューモーダルを開き"AdminPage"をクリックすると管理者画面に遷移します
    - test5@test.com / test5pass   // 店舗代表者(Manager)
      - ログイン画面は同じで、ログイン後メニューモーダルを開き"ManagerPage"をクリックすると店舗代表者の管理画面に遷移します

## 他のリポジトリ
- 他のリポジトリはありません。

## 機能一覧
- 会員登録
- ログイン
- ログアウト
- メール認証(本人確認) 【追加実装分】
- ユーザー情報取得
- ユーザー飲食店お気に入り一覧取得
- ユーザー飲食店予約情報取得
- 飲食店一覧取得
- 飲食店詳細取得
- 飲食店お気に入り追加
- 飲食店お気に入り削除
- 飲食店予約情報追加
- 飲食店予約情報変更【追加実装分】
- 飲食店予約情報削除
- エリアで検索する
- ジャンルで検索する
- 店名で検索する
- 評価・コメント送信【追加実装分】
- 管理画面（管理者）【追加実装分】
  - 店舗代表者権限を付与・削除
  - 全ユーザーへのお知らせ送信
- 管理画面（店舗代表者）【追加実装分】
  - 店舗情報作成
  - 店舗情報更新
  - 予約情報確認
  - ユーザーへのお知らせ送信（全ユーザー／店舗来店履歴あるユーザー／予約中の個別ユーザー）
- 画像ストレージ保存【追加実装分】
- 予約当日リマインダー【追加実装分】
- QRコード作成・表示【追加実装分】
- 決済機能（STRIPE）【追加実装分】
- バリデーション(会員登録、ログイン、予約追加、飲食店登録）【一部追加実装分を含む】

## 使用技術
- PHP 7.4.9
- Laravel Framework 8.83.8
- MySQL 8.0.26
- nginx 1.21.1laravel/fortify 1.19
- livewire/livewire 2.12
- jquery 3.6.0
- simplesoftwareio/simple-qrcode 4.2
- redis_version:8.2.1

## テーブル設計
<img width="1337" height="970" alt="スクリーンショット 2025-09-20 164506" src="https://github.com/user-attachments/assets/8a53847a-30c5-4c27-ab97-10aaa5480fbf" />
<img width="1336" height="1315" alt="スクリーンショット 2025-09-20 164545" src="https://github.com/user-attachments/assets/e0b2ae93-5fd2-4d58-9631-5e3c048be341" />

## ER図
<img width="941" height="605" alt="image" src="https://github.com/user-attachments/assets/10d8e8d3-2fdd-4d51-9d5b-d0e20d8b7b60" />


## 環境構築
- Dockerビルド
  - git clone git@github.com:subaru-tm/Rese.git
  - cd Rese/
  - docker-compose up -d --build
- Laravel環境構築
  - doker-compose exec php bash
  - composer install
  - なお .envの修正は不要です
  - php artisan key:generate
- マイグレーション、シーダー(PHPコンテナ内にて)
  -  php artisan migrate
  -  php artisan db:seed
- 以上まででアプリケーションは動く想定ですが、ブラウザを開いてエラーが出たらエラー内容次第ですが、権限付与で解決することが多いです。
  - (プロジェクトルートディレクトリでの例) sudo chmod -R 777 ./*
  - (少々危なっかしいコマンドだとも思いますが、今回はそこまで影響ないと考えます)

- タスクスケジューラでのメール送信実行はqueueを使用しています。このため実行時には下記コマンドにてqueueを稼働させてください
  - (プロジェクトルートディレクトリでの実行。PHPコンテナ内での実行も可(その場合は、先頭からの"docker-compose exec php"は不要)
  - docker-compose exec php php artisan queue:work --queue=emails
- メールはmailtrapを使用していますのでご確認の際は下記にてログインください
  - https://mailtrap.io/
    - ログイン(メアド):pleiades_tm@yahoo.co.jp
      - (参考)アカウントID：2330889
    - パスワード　：Test1@laravel
  - なお、大変恐縮ですが、無料アカウントの都合でsandboxの受信制限で9月は残り5件しか受信できません。
    - テストコードなどでは自動実行されないようにしていますので、ご確認される際は明示的に画面からの「送信」ボタンにて送信をお願いします。
      - 管理者等からのお知らせの場合、全ユーザーや来店履歴ありのユーザーで複数宛先の場合でも、メール送信の場合は１件のメールです。
      - 一方、リマインダーで送信する場合、ユーザーごとに1件ずつ送られます。このため予約当日、のユーザーが複数いたら、その人数分メールが送られます。
        - mailtrapの制限だと思いますが、短時間で連続して送信するとエラーとなるので、queueでの送信としつつ、1件送ったらsleep(20)で間隔をあけています。

## 他の連絡事項（主に本番／開発／AWS環境について）
- 上記にて構築した環境が開発環境になります。.envファイルが開発環境用です。
- ローカルでの本番環境について
  - .env.productionファイルを本番用の環境変数として追加しています
  - また、本番環境用にdocker-compose.prod.ymlも追加作成していて、開発環境と共通のdocker-compose.ymlに対して追加・更新します
    - 【補足】今回は具体的には、環境変数を.env.productionを見るように追加のみしています。
  - DBは開発用と同じくmysqlコンテナを使用しますが、データベース名を"laravel_production_db"として区分します
  - 本番環境への切り替え手順は次の通りです。
    - docker-compose stop  // 開発環境立ち上がっている前提として、コンテナを停止させます
    - docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
      - 【補足】コンテナ起動にて、docker-compose.prod.ymlも追加で読み込みます
    - 以上で本番環境に切り替わっているはずですが、念のため、および確認コマンドです。
      - docker-compose exec php bash
      - php artisan config:clear
      - php artisan tinker
      - env('APP_ENV');
        - "production" が返ってきたらOK
      - config('database.connections.mysql.database');
        - "laravel_production_db" が返ってきたらOK
    - 問題なければマイグレーション、シーダーを実行して本番環境への切り替え完了です
      - php artisan migrate
      - php artisan db:seed
    - ブラウザからのURLは開発環境と同様です。

  - なお、本番環境から開発環境へ戻す場合、次の手順を実行してください
    - docker-compose stop
    - docker-compose -up -d  // ymlファイルを指定しないので、docker-compose.ymlのみ読込み
    - 以降、確認コマンドです(本番時と同様ですが念のため)
      - docker-compose exec php bash
      - php artisan config:clear
      - php artisan tinker
      - env('APP_ENV');
        - "local" が返ってきたらOK
      - config('database.connections.mysql.database');
        - "laravel_db" が返ってきたらOK

- AWS環境について 
