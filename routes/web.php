<?php


use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/',function () {

    $items=db::table('curries')->get();
    return view('index',['items'=>$items]);
});
Route::get('/detail/{id}', function($id){
    $item = DB::select('SELECT * FROM curries where id = ?',[$id]);
    return view('detail', [
        "item" => $item
    ]);
});

// カートに入れる
Route::post('/cart/{id}', function($id){
    $item = DB::select("SELECT * FROM curries where id = ?",[$id]);//idが一致するものをvegetableテーブルから検索、取得
    $items = session()->get("items",[]); //セッションデータを取得、nullの場合は空の配列
    $items[] = $item; // 取得したデータにオブジェクトを保存
    session()->put("items", $items); //取得したデータをsessionに保存。 $_SESSION["items"] に保存するのと同じ
    return redirect("/cart"); //カートのページへリダイレクト
});

// カートの中を一覧表示
Route::get('/cart', function(){
    $cart =session()->get("items",[]);
    return view("cart", [ //データを渡してビューを表示
        "items" => $cart
    ]);
});

// 商品を削除
Route::get('/delete/{index}', function($index){
    session()->forget("items.$index");
    return redirect("/cart");
});

// カートを空にする
Route::get('/delete/all', function(){
    session()->flush();
    return redirect("/cart"); //カートのページへリダイレクト
});