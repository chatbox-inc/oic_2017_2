# 11. 課題発表 ユニットテストの記述

自分が書いた古いコードの動作確認を毎回ブラウザで確認するのは本当に退屈で嫌になる作業です。

あわよくば、自動で、一括で動作を検証してくれるような仕組みが欲しい…といった願いを叶えてくれるのがユニットテストの仕組みです。

先週の資料で紹介した、クラスの作成をベースに、自分が作成したクラスコードの動作検証を一括で実施するような、ユニットテストの記述を行ってみましょう。

## ユニットテストの記述

ユニットテストは `/tests` フォルダに記述していきます。

サンプルで `Unit/ExampleTest.php` というファイルが存在しているので、ソレを確認してみましょう。

````
<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }
}
````
`$this->assertTrue()`は引数の中身が true かどうかを判定するテスト用関数です。

例えば足し算の結果をテストする処理を記述する場合
以下のようになります。

````
    public function testBasicTest()
    {
        $a = 2;
        $b = 1;
        
        $this->assertTrue($a+$b === 3);
    }
````

テストの実行は `php vendor/bin/phpunit` で実施します。

````
$ php vendor/bin/phpunit 
PHPUnit 6.4.4 by Sebastian Bergmann and contributors.

..........                                                        2 / 2 (100%)

Time: 2.03 seconds, Memory: 16.00MB

OK (2 tests, 2 assertions)
````

テストを実行すると `tests` 内に書かれたコードが自動的に実行します。
`assertTrue`で記述された箇所が false になった場合自動的にエラーが返されます。
例えば先の足し算のテストで 結果を 4 に書き換えて実行してみると良いでしょう。

## 自作クラスの検証

例えばカートの中身を管理する以下のようなクラスを想定します。


````
class Cart {
    // カートに商品を追加する。
    public function addItem($item){ ... }
    // カート全体の商品を取得する。
    public function getItems(){ ... }
    // カート内の合計金額を取得する。
    public function sumPrice(){ ... }
} 
````

このクラスの動きを検証するためのテストは以下のような形になります。

`tests/Unit`フォルダ内に `CartTest.php` と言うかたちで作成します。

````
<?php

namespace Tests\Unit;

class CartTest extends TestCase
{
    public function testAddCartTest()
    {
        $cart = new Cart();
        
        // 最初は空であることのテスト
        $items = $cart->getItems();
        $this->assertTrue(count($items) === 0);    
    
        // 追加した商品が取り出せることの検証
        $item = ... // ダミーの商品情報
        $cart = $cart->addItem($item);
        $items = $cart->getItems();
        $this->assertTrue(count($items) === 1);    
    }
}
````

このような形でテストを記述しておけば、後々コードを見返したときやバグの調査などで、どこのクラスがおかしくなっているのか、どこまで正常にうごくのか、簡単に確認することが出来るようになります。
大規模なコードや長期的にメンテナンスするコードなどでは、是非ユニットテストの導入を検討してください。

ユニットテストでは 他にも様々な `assertXxxxx`系の関数を使用したりグループ分けを行ったりなど行うことが出来ます。
Laravel で使用されている PHPUnit と呼ばれるテストの仕組みは、PHPシステムのテストの場面のほぼ大多数占めるケースで利用されている非常に一般的なものです。

日本語版のドキュメントも用意されているので、以下の公式サイトを見ながらテストに関するより詳しい情報を見つつ、テストを使った開発にチャレンジしてみてください。

https://phpunit.de/index.html

## 本日の出席課題

課題発表申込書に内容を加筆して制作の状況を報告。
