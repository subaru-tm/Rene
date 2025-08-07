# Rene (飲食店予約サービス)

## 環境構築
- Dockerビルド
  - git clone git@github.com:Estra-Coachtech/laravel-docker-template.git
  - mv laravel-docker-template/ Rene/
  - docker-compose up -d --build
- gitリモートリポジトリの変更
  - git remote set-url origin git@github.com:subaru-tm/Rene.git
- Laravel環境構築
  - doker-compose exec php bash
  - composer install
  - cp .env.example .env  // 環境変数を設定
  - php artisan key:generate
  - composer require laravel/fortify
    - php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
  - composer require laravel/ui // fortify標準のview等(register,login)を使用するためインストール
    - php artisan ui bootstrap --auth
  - 【未実行】php artisan storage:link  // シンボリックリンク作成
## 開発環境

## 使用技術
- laravel/fortify 1.19
