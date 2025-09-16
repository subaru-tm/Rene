# Rese (飲食店予約サービス)

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

## 使用技術
- laravel/fortify 1.19
- livewire/livewire 2.12
