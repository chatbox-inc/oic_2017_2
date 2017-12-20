# 3. DATABASE設計とモデルの制作 1

本日の目標

- Database にテーブルを作成
- テーブルに商品データを突っ込む
- 画面に商品情報を一覧表示する
- 画面に商品情報を詳細表示する

## 必要なデータベース構成の設計

画面の準備が終わったら、画面に表示する商品情報を保存するための、データベーステーブルを作成してみましょう。

データベースの作成処理は、 `database/migrations` フォルダに記述していきます。

migration ファイルは データベースのテーブル構成を記述するファイルです。新しいmigration ファイルは以下のようにして作成することが出来ます。

```bash
$ php artisan make:migration items
```

`database/migrations` フォルダに新しく　`{日付}_items.php` ファイルが作成されたら実際に中身を確認してみましょう。

今回は商品を保存するテーブルを作成します。

```php
class Items extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('price');
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
}
```

`up`メソドにはテーブルの作成処理を、`down`メソドにはテーブルの削除処理を記述します。

テーブルの作成は`Schema::create` で行います。 第一引数にはテーブル名を記述するので ここでは商品を格納する `items` テーブルを作成しています。

第二引数では、テーブルの列を定義していきます。 `function(){ ... }` 内にテーブルのカラムを追加していきます。

`$table->increments('id');` は ID列の追加です。 `increments` で作成した列は 自動採番の列で行毎にユニークなIDが自動で生成されます。

`$table->string('name')`　は 商品名の列の追加です。 `string` は 文字列の列を作成します。

`$table->integer('price')`　は 価格の列の追加です。 `integer` は 文字列の列を作成します。

他にも `timestamp` で日付列を追加したり, `text`でTEXT列を追加したり出来ます。

自分の必要なDBの構成にあわせて列の構成などを考え、テーブル定義を作成してみましょう。

コードが記述し終わったら以下のコマンドで、データベースを作成します。

````
$ php artisan migrate:refresh
````

:::warning
※ ホストマシン上で実行するとエラーになります。 `vagrant ssh` コマンドで Vagrant 環境にログインしてから実行してください。
:::

## サンプルデータの格納

前期に実施したデータベースのInsert文をつかって、データベースにサンプルの商品情報を格納してみましょう。

サンプルデータの格納には Seed と呼ばれる機能を利用します。

`database/seeds/DatabaseSeeder.php`  に Database へのInsert処理を記述していきます。

````
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::insert("insert into items (name,price) value (?,?)",[
            "サンプル商品", 200
        ])
    }
````

シードの実行は以下のコマンドで実施します。

````
$ php artisan db:seed
````

:::warning
※ ホストマシン上で実行するとエラーになります。 `vagrant ssh` コマンドで Vagrant 環境にログインしてから実行してください。
:::


コレを利用して、DBに自分で必要な商品データを 2-6 件程度格納してみましょう。


## サンプルデータを画面に出力する。

格納されたサンプルデータを利用してDBからデータを取得してみましょう。

ルート内で SQL を発行して DBのデータを取得することが出来ます。

```php
Route::get("/",function(){
    $items = DB::select("SELECT * FROM items");
    return view("toppage",[
        "items" => $items
    ]);
});
```

画面のファイル`views/toppage.php` では以下のように取得した商品データをループさせて順に表示します。

```php
<?php foreach($items as $item): ?>
<div>
    <?= $item->name ?> 
    <?= $item->price ?> 円
    <a href="/detail/<?= $item->id ?>"> 詳細 </a>
</div>
<?php endforeach; ?> 
```


詳細画面でも同様に SQL を発行して DBのデータを取得できます。

````
Route::get("/detail/{item_id}",function($itemId){    
    $items = DB::select("SELECT * FROM items where id = ?",[$itemId]);
    if(count($items)){
        return view("detail",[
            "item" => $items[0]
        ]);    
    }else{
        return abort(404);
    }
});
````

URL から 表示する対象商品のIDを受取、データを画面に表示しています。URLからのパラメータの受け取り方は 過去のドキュメントを参照してください。

DBの結果は常に リストで取得されるため、 count で結果が存在するかの確認をしています。これにより適当なURLが渡された場合などに else ブロックで 適切な 404 処理を行うことが出来ます。


詳細画面のファイル`views/detail.php` では以下のように取得した商品データをループさせて順に表示します。

````
<div>
    <?= $item->name ?> 
    <?= $item->price ?> 円
    <a href=""> カートに追加する </a>
</div>
````

:::warning
※ DBの処理をルートに記述するとルートファイルが煩雑になります。余裕のある人はコントローラクラスの導入を検討してください。
:::

## 本日の出席課題

Slack のDMで以下をUPして進捗を報告しに来てください

- 商品テーブルのマイグレーションファイル
- 画面のスクショ 数点
- どんなECサイトをつくるか（変化、進展あれば）
