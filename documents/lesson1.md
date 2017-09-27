# Webアプリケーション開発

Github リポジトリ

====

# 1. Webシステムを作ろう

授業の概要

- 後期授業の概要
- 環境構築
- 画面設計を考える

## 後期授業概要

後期授業では PHP/MySQL/Laravel を使用した ECサイト制作を行います。

前期の課題制作と異なり、複数画面の構成があるWebアプリケーションで、
より高度なソースの管理、コードの理解などが求められます。

とはいえ、基本は前期に制作したタスクリストと同じ、
データベースからのデータの読み込み・書き込みで構成されるアプリケーションなので
心配する必要はありません。前期の復習を元に頑張って課題制作を進めてください。

授業のまとめ、ドキュメントは Github 上にて公開していきます。
質問・出席・相談等は、前記同様 Slack にて行います。

## ECサイト制作

後期の製作課題は以下のようなECサイトです。

http://oic-ec.herokuapp.com/

商品の一覧が表示されて、カートに商品の選択を保存することが出来ます。

カートに商品を保存した後の処理に感しては、それぞれで工夫して実装してください。

前記同様、ただの「ECサンプルアプリ」ではなく、それぞれの工夫を凝らした
コンセプトのあるECサイトを製作してもらいます。

コンセプトに応じて商品には 価格以外の属性 色やサイズなどが要求されると思いますので、
それを各自実装してもらいます。

## 環境構築


Github のリポジトリからデータを Clone してきてください。

https://github.com/chatbox-inc/oic_2017_2

夏季課題で Git が習得出来ていない方は 緑のボタンから Download して来てもらってもOKです。
(Git の習得 / Github の利用は評価項目に含めます。後期中の習得をおすすめします。)

Clone / ダウンロードしてファイルが展開できたら terminalでフォルダの中に移動し以下のコマンドを叩いてください。

````
$ ssh-keygen -t rsa
$ composer install 
$ cp .env.example .env
$ php artisan key:generate
$ vendor\\bin\\homestead make
$ vagrant up
````

Mac や Linux を使用している方は以下のコマンドになります。

````
$ ssh-keygen -t rsa
$ composer install 
$ cp .env.example .env
$ php artisan key:generate
$ php vendor/bin/homestead make
$ vagrant up
````

Vagrant 起動後 http://192.168.10.10 で画面が表示されれば成功です。

### Error1

以下のエラーが表示される場合、修正が必要です。

````
 The requested address is not valid in its context. - connect(2) for "0.0.0.0" port 8000 (Errno::EADDRNOTAVAIL)
````

`vendor/laravel/homestead/scripts/homestead.rb`をMery などのエディタで開き、87行目あたりに以下の修正を入れます。

````
config.vm.network "forwarded_port", guest: guest, host: host, auto_correct: true
↓
config.vm.network "forwarded_port", guest: guest, host: host, host_ip: "127.0.0.1", auto_correct: true
````

http://qiita.com/harunbu/items/da277e04175c9ddfb080

## 画面一覧を考える

どのようなECサイトを作成するかを考え、画面の一覧をイメージしてみましょう。

サンプルのECサイトでは、

- トップ(商品一覧)
- 商品詳細 
- カート 

の３つの画面が存在しています。 それぞれのURLを考え、HTMLを書きながら画面の製作を始めるところからスタートです。

画面の作成は `route/web.php`に

````
Route::get('{URL}', function () {
    return view('{TEMPLATE}');
});
````

で行います。

※ {}内は書き換え。

画面のテンプレートには

https://html5up.net/
https://templated.co/

などのテンプレートサイトを参考にするか、

[TwitterBootstrap](https://getbootstrap.com/) などのCSSフレームワークを使ってみるとよいでしょう。



