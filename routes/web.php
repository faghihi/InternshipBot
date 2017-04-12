<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/testbot','telegramtestcontroller@index');
Route::get('/testbot1','telegramtestcontroller@sendtext');

Route::get('/set', function () {
    $res = Telegram::setWebhook([
        'url' => 'https://hamyad.herokuapp.com/343139142:AAGCpIOGVwFcbbJL56sLVSXUgz8zO3jPc34/webhook'
    ]);
    dd($res);

});

Route::post('/343139142:AAGCpIOGVwFcbbJL56sLVSXUgz8zO3jPc34/webhook','TelegramController@run');

Route::get('testdatabase',function (){
    return \App\Conversation::all();
});
Route::get('testdatabase1',function (){
    return \App\Data::all();
});
Route::get('test',function (){
    $dummy=\Config::get('majors.majors');
    $keyboard=[];
    foreach ($dummy as $key=>$value) {
        $keyboard[] = $key;
    }
    return $keyboard;
});

Route::get('test2',function (){
    $data1='111';
    return \Config::get("majors.$data1");
});

