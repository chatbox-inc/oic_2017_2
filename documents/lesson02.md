# Webアプリケーション開発

Github リポジトリ

https://github.com/chatbox-inc/oic_2017_2

====

# 2. Webシステムの設計

授業の概要

- 画面、処理の設計
- 必要な処理を考える
- データベースの準備

## 画面・処理の一覧を考える

複数の画面をもつアプリケーションの作成では、機能や画面構成の整理が必要不可欠です。
以下のスプレッドシートのように、自分が作成するシステムでの画面構成、処理内容などを確認してみましょう。

https://docs.google.com/spreadsheets/d/1LPNIfAN1nvIpDzhONmueLNlWvkMmtrNTI5J-BQS-Yww/edit#gid=0

画面だけでなく処理の把握も必要です。

- カートへデータを追加する。
- 注文情報を保存する

などの追加更新処理は通常、画面の表示とは区別して POST メソドで行われます。これらをどのようなアドレスで実施するか、最初にしっかり決めておくと制作がスムーズに進みます。

### ルートの記述

以下の用にルートを追加してそれぞれの処理を記述していきましょう。

````
Route::get("/",function(){
    return view("toppage");
});

Route::get("/detail/",function(){
    return view("detail");
});

Route::get("/cart",function(){
    return view("cart");
});
````

※ 複数の画面構成においては、ルートの処理はどんどん膨らんできます。学習に余裕があれば、コントローラの導入を検討してみてください。

### ルートパラメータの取得

URLの `?`の後ろにパラメータを付け、処理側で利用することが出来ます。

例えば以下のルートに `http://192.168.10.10/detail?name=john` とアクセスした場合、

````
Route::get("/detail/",function(){
    $name = request("name");
    return view("detail",[
        "name" => $name
    ]);
});
````

`$name` には `john` が入ってきます。

`?` を使わずに直接URLの中にパラメータを含ませることも出来ます。

````
Route::get("/detail/{item_id}",function($item_id){
    return view("detail",[
        "item_id" => $item_id
    ]);
});
````

上記のルートに `http://http://192.168.10.10/detail/123` のURLでアクセスした場合、　`$item_id` には `123` が入ってきます。

## plus Alpha


ルートが複数になってくると `web.php` は複雑になってきます。Controllerの導入を検討してください。

https://laravel.com/docs/5.5/controllers

全てのページで同じ `<head>` タグなどを記述していくと、後々管理が煩雑になります。
画面の中で共通化したいパーツなども出てくるかもしれません。
高度な画面の記述に挑戦したい場合は Blade の導入を検討してみてください。

https://laravel.com/docs/5.5/blade

## 本日の出席課題

Slack のDMで以下をUPして進捗を報告しに来てください

- route/web.php
- 画面のスクショ 数点
- どんなECサイトをつくるか（決まっていれば）
