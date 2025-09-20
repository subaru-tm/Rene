# Rese (飲食店予約サービス)
- ユーザーはグループ会社が運営する飲食店情報を閲覧でき、更に会員登録すると予約（変更可）や来店後の評価・コメントを送信できる
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

## 環境構築
- Dockerビルド
  - git clone git@github.com:Estra-Coachtech/laravel-docker-template.git
  - mv laravel-docker-template/ Rese/
  - docker-compose up -d --build
- gitリモートリポジトリの変更
  - git remote set-url origin git@github.com:subaru-tm/Rese.git
- Laravel環境構築
  - doker-compose exec php bash
  - composer install
  - cp .env.example .env  // 環境変数を設定
  - php artisan key:generate
  - composer require laravel/fortify
    - php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
  - composer require laravel/ui // fortify標準のview等(register,login)を使用するためインストール
    - php artisan ui bootstrap --auth
  - php artisan storage:link  // シンボリックリンク作成
  - composer require livewire/livewire  // menuをモーダル表示にするためにインストール
    - php artisan make:livewire Modal
  - タスクスケジューラについて
    - 今回の実装方法は、Artisanコマンドで処理を作成し、app/Console/Kernel.phpにてスケジュール定義をしています。
      - (PHPコンテナ内にて)
      - php artisan make:command Sendmails
        - 作成されたファイル : app/Console/Commands/SendEmails.php
    - また定義したスケジューラの実行のためcronをエントリしています
      - (プロジェクトルートディレクトリにて(コンテナの外))
      - sudo apt install php-gd // crontabを編集するにあたりエラーが出たためgdをインストール
      - crontab -e //下記1行を追加。
        - * * * * * cd /home/pleiades_tm/coachtech/laravel/Rese && docker-compose exec php php artisan schedule:run >> /dev/null 2>&1
    - ★重要★なお、タスクスケジューラでのメール送信実行はqueueを使用しています。このため実行時には下記コマンドにてqueueを稼働させてください
      - (プロジェクトルートディレクトリでの実行を想定。PHPコンテナ内での実行も可(その場合は、先頭からの"docker-compose exec php"は不要)
      - docker-compose exec php php artisan queue:work --queue=emails
    - 
## 開発環境
- 上記にて構築した環境が開発環境になります

## 使用技術
- laravel/fortify 1.19
- livewire/livewire 2.12
- simplesoftwareio/simple-qrcode 4.2
