# 8. メール/Lineを使ったメッセージ転送

メッセージ送信や決済など外部サービスを利用したシステム構築を行う様々な場面で、API通信によるシステム連携が必要になります。

PHPにおけるシステム連携では Guzzle とよばれるライブラリを利用するのが一般的です。

https://github.com/guzzle/guzzle

Laravel のシステムに Guzzle をインストールする場合、プロジェクトのルートで以下のコマンドを実行します。

````
composer require guzzlehttp/guzzle
````

これでvendor ディレクトリの中にGuzzleライブラリがインストールされ、利用可能になりました。

## メールの送信

今回はメール送信に `mailgun` というサービスを利用してみます。

https://app.mailgun.com

Mailgun でアカウントを作成すると、すぐ画面にサンプルコードが表示されますので、コレを控えておきましょう。

### メール送信の準備

Laravel で Mailgunを使用するための、準備を行っていきます。

まず、Mailgun を利用するためのライブラリをインストールします。

````
composer require mailgun/mailgun-php php-http/curl-client guzzlehttp/psr7
````

つぎにMailgunのログイン画面から、Domainのタブを選択し、Authorized Recipientsの追加を行います。
自分のメールアドレスを追加しておくとよいでしょう。

クレジットカードを登録していないMailgunのアカウントでは、Authorized Recipientsへ登録されたユーザへのみメールが送信可能です。

### メールを送信する

準備ができたらメールを送信するコードを書いていきましょう。

````
$mg = Mailgun\Mailgun::create('{YOUR_APIKEY}');

# Now, compose and send your message.
# $mg->messages()->send($domain, $params);
$mg->messages()->send('{YOUR_DOMAIN}', [
    'from'    => '{YOUR_EMAIL}',
    'to'      => '{YOUR_EMAIL}',
    'subject' => 'The PHP SDK is awesome!',
    'text'    => 'It is so simple to send a message.'
]);})
````

上記コードの{}の部分は適宜修正してください。

- YOUR_APIKEY: Mailgun ログイン画面の右上、ユーザ名からSecurity を選択したところにある Private API Key の項目
- YOUR_DOMAIN: Mailgun ログイン画面のDomainのタブから リストで表示されているDomain Name 
- YOUR_EMAIL:  Authorized Recipients に追加したメールアドレス

## Line でメッセージ送信する

Lineでのメッセージ送信には Line Notifyが便利です。

https://notify-bot.line.me/ja/

アカウントを登録して、マイページからトークンを発行します。

次にAPIドキュメントを見ながら通知APIの情報を取得していきます。

https://notify-bot.line.me/doc/ja/

通知系は`POST` `https://notify-api.line.me/api/notify`でメッセージ送信が可能と記載があるのでこれをもとにリクエストを組み立てていきます。

````
$client = new \GuzzleHttp\Client();
$res = $client->request('POST', 'https://notify-api.line.me/api/notify',[
    "headers" => [
        "Authorization" => "Bearer {ACCESS_TOKEN}",
    ],
    "form_params" => [
        "message" => "商品が売れました"
    ]
]);
````

サービスを利用する際にはWeb API の利用が不可欠ですが、Mailgunのようにライブラリがないサービスでは、自分でAPIのリクエストを生成する必要があります。

大抵のリクエスト送信は Guzzle を使用して行うことが出来るので、気になるサービスがあれば Guzzle を利用したWebサービスの活用に是非チャレンジしてみてください。

## 本日の出席課題

課題発表申込書に内容を加筆して制作の状況を報告。
