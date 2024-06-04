<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;

//Route::match(['GET','POST'], '/{id}', function($id)
//{
//    echo $id;
////    return view('welcome');
//});
//Route::post('/home/{o}',function ($o){
//    echo $o;
//});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    Route::middleware('home')->get('/home',function (){
        return view('admin.home');
    });
});
require __DIR__ . '/api.php';
