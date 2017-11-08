# 6. バリデーションとリクエストハンドリング

## フォームの構築

購入画面を作成し、住所項目を送信できるようにしてみましょう。

routes/web.php

````
//購入画面
Route::get("buy",function(){
    return view("buy");
});

//購入処理
Route::post("buy",function(){
    return redirect("thanks");
});

//購入後画面
Route::get("thanks",function(){
    return view("thanks");
});
````

views/buy.php

````
<form action="/buy" method="POST">
    <?=csrf_field()?>
    <input type="text" name="name">
    <input type="text" name="email">
    <input type="text" name="tel">
    <input type="text" name="address">
</form>
````

views/thanks.php
````
<p>購入ありがとうございました。</p>

````

入力フォームにおいては、例えば、住所を必須にしたり、Email 形式でないEmailの入力を弾いたりしたいケースも有るかと思います。

バリデーションはこのようなフォーム入力において、入力値のチェックをしたり、入力を整えたりする働きを持ちます。

## バリデーションとリクエストハンドリング

購入処理のルートを以下のような形で調整してみましょう。

````
//購入処理
Route::post("buy",function(){
    $validator = Validator::make(request()->all(), [
        'name' => ['required'],
        'email' => ['required'],
    ])->validate();
    return redirect("thanks");
});
````

ここでは、名前とEmailの入力を必須、としています。

購入処理のルートに上記の記述を加えると、名前とEmailどちらかが空の状態では、次の画面に遷移できなくなります。

遷移できないだけでは、ユーザは何が起こったのかわからないので、画面にエラーメッセージを表示してみましょう。

Laravel を利用したエラー処理ではView 内でバリデーションエラーに関する変数`error`が利用可能です。
エラーメッセージを表示するには、以下のようなコードをフォームの中に表示します。

views/buy.php

````
<?php if($errors->first('name')):?>
    名前を入力してください。
<?php endif;?>

<?php if($errors->first('email')):?>
    Emailを入力してください。
<?php endif;?>
````

これで画面にエラーメッセージが表示されるはずです。

## 形式チェックの実施

required以外にも様々なバリデーション処理が用意されています。

````
//購入処理
Route::post("buy",function(){
    $validator = Validator::make(request()->all(), [
        'name' => ['required','max:10'],
        'email' => ['required','email'],
    ])->validate();
    return redirect("thanks");
});
````

`Email`は正しいEmail形式の入力を要求します。

`max:10`は10文字以内の入力を要求します。

他にもさまざまなルールが利用可能です。詳細はドキュメントを確認してください。

https://laravel.com/docs/5.5/validation#available-validation-rules

## 古いフォームデータの維持

バリデーションはきっちり実施できるようになりましたが、これではエラーが起こるたびに入力が空になってしまいます。

古いフォームの状態を保持するには old 関数が便利です。

old関数はひとつ前のフォームの値を保持してくれているので、フォームのvalue 属性と組み合わせて以下のように記述すると、値を保持することができます。

````
<input type="text" name="name" value="<?=old('name') ?>">
````

## 情報の保持

これでフォームの作成は完了です！あとは入力された情報と、セッション内のカート情報を使って購入情報を記録しましょう。

````
//購入処理
Route::post("buy",function(){
    $validator = Validator::make(request()->all(), [
        'name' => ['required','max:10'],
        'email' => ['required','email'],
    ])->validate();
    // ここで購入情報を記録する。
    return redirect("thanks");
});
````

購入情報の記録方法としてはいろいろありますが、

― DBに保存する
― ファイルに保存する
― Line に転送する
- メールで転送する

など様々な方法があります。自分なりの工夫を生かしていろいろな機能を検討してみてください。

## 本日の出席課題

課題発表申込書に内容を加筆して制作の状況を報告。
