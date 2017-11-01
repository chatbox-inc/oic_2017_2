# 5. セッションを用いたデータ保存の仕組み

今週の目標

- セッションの仕組みを理解する
- セッションへ商品データを追加
- セッションデータを表示する

## セッションの概要

セッションの仕組みを理解するために、簡単な以下のルートを定義してみましょう。

````
Route::get("/session_test",function(){
    $count = request()->session()->get("COUNTER",0);
    $count = $count + 1;
    request()->session()->put("COUNTER",$count);
    return "{$count}回目のアクセスです";
});
````

ブラウザで `/session_test` にアクセスすると、セッションの仕組みが確認できると思います。

最初は 「1回めのアクセスです」と表示され、後にリロードする度にカウントは増えていきます。カウントはブラウザ毎に記録するため、別のブラウザで確認するとまた1からの表示となります。

セッションの値はブラウザ端末毎に個別で格納されます。セッションの管理はクッキーで行っているため、ブラウザからクッキーを消せば最初の 「1回めのアクセスです」の表示に戻ります。

## セッションの格納

では、セッションを使ってカートに追加する処理を記述していきましょう。

カートへの追加 は処理なのでここではPOSTメソドを使ってルートを作成していきます。

以下がURLに渡された商品IDを使用して セッションに商品情報を保存するコードになります。

````
// [POST] /cart/{item_id} カートの追加
Route::post("/cart/{item_id}",function($itemId){
    $items = DB::select("SELECT * FROM items where id = ?",[$itemId]);
    if(count($items)){
        $cartItems = [];
        $cartItems[] = $items[0];
        request()->session()->put("CART",$cartItems);
        return redirect("/cart");    
    }else{
        return abort(404);
    }
});
````

このままではまだ上手く動作しないと思いますが、一行ずつコードを見ていきましょう。


````
// [POST] /cart/{item_id} カートの追加
Route::post("/cart/{item_id}",function($itemId){
    $items = DB::select("SELECT * FROM items where id = ?",[$itemId]);
    if(count($items)){
        ....
    }else{
        return abort(404);
    }
});
````

こちらの記述は 詳細画面でもおなじみのコードです。URLから 商品IDを取得してデータを検索しています。

商品が見つかったら処理を開始し、見つからない(不正なURLの)場合は404ページを表示します。


if 文の中では、セッションを利用してカートデータの保存を行っています。

カートには配列で商品情報を格納していくため、配列を作成してそこにDBから取得した商品情報を格納していきます。DBの結果は必ずリストで返ってくるので、 `$items[0]` という形で最初のデータを選択するのを忘れないようにしましょう。

````
$cartItems = [];
$cartItems[] = $items[0];
request()->session()->put("CART",$cartItems);
````

セッションへのデータ保存は `request()->session()->put()` を利用します。

セッションにはそれぞれ名前をつけてデータを保存する仕組みになっているので、第一引数にはデータの名前 `CART` を入力してカート情報を格納しています。 第二引数には保存したい変数を指定します。

最後に `redirect` を使って画面遷移を行っています。ここではカート画面のURL`/cart` を指定しています。

````
return redirect("/cart");    
````

ルートを作成したら商品詳細画面の「カートに入れる」ボタンを調整します。

POSTでルートを作成しているので POST送信できるように form を使ったコードに修正しましょう。

詳細画面のファイル`views/detail.php` では以下のように取得した商品データをループさせて順に表示します。

````
<div>
    <?= $item->name ?> 
    <?= $item->price ?> 円
    <form action="/cart/<?= $item->id ?>" method="POST">
        <?= csrf_field() ?>
        <input type="submit" value="カートに追加する">        
    </form>
</div>
````

これでボタンを押したタイミングで POST の /cart/{item_id} へ遷移するはずです。

リダイレクト先のカートの画面をまだ作成していないので引続き セッションデータからカートの中身を表示する画面を作成していきましょう。

## セッションの表示


次にカートの画面を作成します。 URL は `/cart` でGETメソドを用いて画面を描画しています。

````
// [GET] /cart カートの表示
Route::get("/cart",function(){
    $cartItems = request()->session()->get("CART",[]);
    return view("cart",[
        "cartItems" => $cartItems
    ]);    
});
````

セッションデータの取得には `request()->session()->get()` を利用します。

セッションにはそれぞれ名前をつけてデータを保存する仕組みになっているので、第一引数にはデータの名前 `CART` を入力してカートの情報を取得しています。

第二引数には セッションに情報が保存されていない場合のデフォルト値を指定することが出来ます。カートの商品は配列で保存していくので、ここでは空の配列を指定しています。

セッションから取得した(or空の配列)をテンプレートファイルに渡せば後は画面側でカートの商品一覧を描画して完了です。

画面のファイル`views/cart.php` では以下のように取得したセッションから取得した商品データ(or空の配列)をループさせて順に表示します。

```php
<p> カートの中身 </p>
<?php foreach($cartItems as $item): ?>
<div>
    <?= $item->name ?> 
    <?= $item->price ?> 円
</div>
<?php endforeach; ?> 
```

これで `/cart` のアドレスでセッションの中に格納された商品の情報が表示されるようになります。

実際に画面を叩いて動作を確認してみましょう。

## セッションのデータを更新する

カートの画面が表示できるようになりましたが、このままではカートに商品が一つしか格納されません。

カートのデータを常に新しく書き換えるのではなく、追記する形式に変更するには、セッションの取得 `get` と セッションの保存 `put` を組み合わせて 以下のようにカートの追加処理を書き換えます。


````
// [POST] /cart/{item_id} カートの追加
Route::post("/cart/{item_id}",function($itemId){
    $items = DB::select("SELECT * FROM items where id = ?",[$itemId]);
    if(count($items)){
        $cartItems = request()->session()->get("CART",[]);
        $cartItems[] = $items[0];
        request()->session()->put("CART",$cartItems);
        return redirect("/cart");    
    }else{
        return abort(404);
    }
});
````

これで以前のカート情報を取得して、商品を追加、という処理になり、順に商品がカートに追加されていくはずです。

## その他のカート処理

セッション内のデータを削除するには 以下のように記述します。

````
request()->session()->forget("CART");
````

また、通常セッションは、上記の`forget` で消さない限り半永久的に残り続けますが、Laravel の flash session の機能を使って１回だけ記録されるセッションを作成することができます。

セッションをflash session として格納する場合、`put` の代わりに`flash`を利用します。

````
request()->session()->flash("message", "カートに商品を追加しました。")
````

`flash`で格納されたセッションは次の画面遷移先でのみ有効です。データの取得には通常のセッション同様`get`を使用します。


カートにデータを追加する処理が出来たら、以下のような機能も実装してみましょう。

- カートを空にするボタンの設置
- カートに追加するボタン押下時のみ動作する
- カート内の合計金額を表示
- 詳細画面で「個数」を選択してからカートにいれられるように

## 本日の出席課題

添付資料「後記課題申し込み」に目を通し、課題申込書を仮でもよいので作成しSlackで送信してください。
