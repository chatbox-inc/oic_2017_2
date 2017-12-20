# 7. Twitter Bootstrap 4 を利用した画面制作

## 補講:個別削除の実装

カート内のデータを個別に削除したいという要望が多かったため、個別削除の実装方法を紹介します。

コードの記述は抜粋のため、どこにどう書くかは各自でしっかり考えて作業してみてください。

### 個別削除ボタンの実装

個別削除の場合、カートの全消去と違い、「何番目の商品を削除するか」という情報が必要になります。

カートない商品の出力には`foreach`を使用していると思いますので、以下のようにフォームを記述して、URLに何番目の削除、という情報を渡すことができます。

````
<?php foreach($items as $index => $item):?>
...
<form action="/cart/delete/<?=$index?>" method="POST">
...
<?php endforeach; ?>

````

上記のようにforeachでは`$items as $index => $item`と書くことで、`$index`に配列のキーの情報を渡すことができます。

ここでは、カート内商品のIndex が入ってくるはずなので、これを利用してカートない商品の削除を実施します。

###　カート内商品の削除

削除とは言ってもカート情報を完全に削除するわけではないので、ひとつ商品を取り除いた状態で、カート情報を更新する、形になります。

よってLesson5で紹介したカートの更新と同じ形で、処理できるはずです。

以下はLesson5で紹介したカートの更新処理です。

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

今回カートの個別削除を実装するにあたって、もちろん、新しいルートを定義します。

````
// [POST] /cart/delete/{index} カートの個別削除
Route::post("/cart/delete/{index}",function($index){
    ....
});
````

URLにはカート内での順番、が渡ってくるはずです。

商品IDを指定して商品を追加した時のようなDBへの検索処理は必要ありません。

ルート内では、以下の処理を順に行えばOKです。

- 1.セッションからカート情報を取り出す。
- 2.取り出したカート情報からURL経由で渡された番号の商品を削除する。
- 3.削除したカート情報でセッションを更新する。
- 4.希望のページにリダイレクトさせる。

たとえば、取り出したカート情報を`$cartItems`という変数に格納している場合、2.の削除処理は以下のような記述で実装できます。

````
unset($cartItems[$index]);
````

`$index` はURL経由で渡された番号です。

これで、`$cartItems` から指定された番号の商品が削除されるため、残り3,4の記述を加えれば完成です。

### plus Alpha

個別商品の削除が実装できたら、今度は同一の商品が追加されたタイミングで 個数2ｺ となるようなカートの実装に挑戦してみましょう。

実装の方法は様々になりますがセッションのカート情報の持ち方を変更すれば、削除の方法も異なってきます。

さまざまな形式のデータをうまく利用できるようになって、セッションの処理や配列の加工について、慣れを積み重ねていきましょう。

## Twitter Bootstrap 4 の利用

Twitter Bootstrap ４ は 広く一般的に用いられている、CSSフレームワークです。

http://getbootstrap.com/

PHP 開発で　Laravel フレームワークを利用したのと同様、 CSS においても フレームワークを利用することで様々なメリットが得られます。

- コードの設計や書き方にある程度の共通化したやり方が与えられて、チームメンバーのコードスタイルが統一化される。
- 複雑な記述や、陥りやすい欠陥などをキレイにライブラリ側でサポートしてくれる。
- 良くある機能や典型的な実装パターンについて、少ない記述量で実現できるツールを用意してくれている。

Twitter Bootstrap は 下記Getting Started の項目にもあるように、CDNが提供されているため、
ひとつのlink要素と、３つのscript要素をHTML内に記述するだけで使い始めることができます。

http://getbootstrap.com/docs/4.0/getting-started/introduction/ 

[example](http://getbootstrap.com/docs/4.0/examples/)ではいくつかの利用例も紹介されているので参考に確認してみてください。

#＃ Layout の組み方

Bootstrap におけるレイアウト は `.container > .row > .col`　の階層で構成されます。

````
<div class="container">
  <div class="row">
    <div class="col">
      1 of 2
    </div>
    <div class="col">
      2 of 2
    </div>
  </div>
</div>
````

Containerはサイト全体の構成を管理する枠組みで、横の１行ひとまとまりを Row で管理します。

Row の中のアイテムは Col で管理され、 一つのRow が複数の Col を保つ場合、それらは横並びで表示されます。

複数のCol の中で特に大きく表示したいものがある場合、以下のように`.col-5` などを使ってサイズを指定します。

````
<div class="container">
  <div class="row">
    <div class="col">
      1 of 3
    </div>
    <div class="col-5">
      2 of 3 (wider)
    </div>
    <div class="col">
      3 of 3
    </div>
  </div>
</div>
````

数字によるサイズの指定は、 Row を 12分割した基準で行われます。

`.col-5` とした場合は、 Row の 5/12 のサイズが確保されるわけです。

要素の横並びは、PC向けのページでは有用ですが、スマホ向けの画面サイズではキレイに表示されないケースが多いでしょう。

画面幅が小さいときには全幅表示を行いたい場合、 `.col-sm` といったクラスが利用できます。

````
<div class="row">
  <div class="col-sm">col-sm</div>
  <div class="col-sm">col-sm</div>
  <div class="col-sm">col-sm</div>
</div>
````

その他にも要素に関する様々なレイアウトのクラスが用意されているので、実際に利用しながら確認して見てください。

http://getbootstrap.com/docs/4.0/layout/grid/

## その他の Bootstrap コンポーネント

ボタンやフォーム要素、モーダルやカード表示など、Webで良く利用される様々なUIパーツがCSSの記述なしで利用できるようになっています。

Button:
http://getbootstrap.com/docs/4.0/components/buttons/
Forms:
http://getbootstrap.com/docs/4.0/components/forms/
Modal:
http://getbootstrap.com/docs/4.0/components/modal/
Card:
http://getbootstrap.com/docs/4.0/components/card/

## 本日の出席課題

課題発表申込書に内容を加筆して制作の状況を報告。
