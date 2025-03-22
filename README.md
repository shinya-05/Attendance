#　アプリケーション名
- coachtechフリマ
  ある企業が開発した独自の勤怠アプリ
  ![alt text](https://github.com/shinya-05/frimaap/blob/main/src/image.png)

## 作成した目的
- ユーザーの勤怠と管理を目的とする

## アプリケーションURL
- 作成中

## 機能一覧
- 登録認証機能、ログイン認証機能、メール認証機能、ログアウト機能、商品の出品購入機能、商品検索機能、コメント送信機能、いいね機能、配送先変更機能、ユーザー情報変更機能、

## 使用技術(実行環境)
- PHP8.3.0
- Laravel8.83.27
- MySQL8.0.26

## ER図
- ![alt text](https://github.com/shinya-05/frimaap/blob/main/src/image-1.png)


## 環境構築
**Dockerビルド**
1. `git clone git@github.com:shinya-05/frimaap.git`
2. DockerDesktopアプリを立ち上げる
3. `docker-compose up -d --build`

> *MacのM1・M2チップのPCの場合、`no matching manifest for linux/arm64/v8 in the manifest list entries`のメッセージが表示されビルドができないことがあります。
エラーが発生する場合は、docker-compose.ymlファイルの「mysql」内に「platform」の項目を追加で記載してください*
``` bash
mysql:
    platform: linux/x86_64(この文追加)
    image: mysql:8.0.26
    environment:
```

**Laravel環境構築**
1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. .envに以下の環境変数を追加
``` text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

``` text
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

``` text
STRIPE_SECRET_KEY=sk_test_51QggNIFTdZQyVkxrGOHr504Av6o7iXHEICt7l0XRRC61uQHsuRrX98qpSIcuxM95Fiwjd9TE7AkCWi797X4vaEkx00GzGNWEAH
STRIPE_PUBLIC_KEY=pk_test_51QggNIFTdZQyVkxrrlkyTBT2F6z9K2zcdXPG3zIfecz52w1AE3H3wKmdq1vknh5e6seyuV94KEnntQnuADQnHO3m00UWXF14Kr
```

5. アプリケーションキーの作成
``` bash
php artisan key:generate
```

6. マイグレーションの実行
``` bash
php artisan migrate
```

7. シーディングの実行
``` bash
php artisan db:seed
```

8. storageディレクトリをpublicに公開
``` bash
php artisan storage:link
```
storage/app/publicにproduct_imagesとprofile_imagesディレクトリを作成

storage/app/publicに下記画像を保存
![alt text](https://github.com/shinya-05/frimaap/blob/main/src/default-item.jpg)

![alt text](https://github.com/shinya-05/frimaap/blob/main/src/default-profile.jpeg)

