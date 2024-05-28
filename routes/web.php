<?php
use App\Http\Middleware\HomeMiddleware;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;
use App\Http\Controllers\AdminController;

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    Route::middleware(HomeMiddleware::class)->get('/',function (){
        return view('admin.home');
    });
    Route::middleware(HomeMiddleware::class)->get('/insert-article/{id}',[AdminController::class,'all_article']);
    Route::middleware(HomeMiddleware::class)->post('/insert-article/{id}',[AdminController::class,'article']);
    Route::middleware(HomeMiddleware::class)->get('/insert-para/{id}',function (){return view('admin.insert_para');});
    Route::middleware(HomeMiddleware::class)->post('/insert-para/{id}',[AdminController::class,'para']);
    Route::middleware(HomeMiddleware::class)->get('/brand',[AdminController::class,'all_brands']);
    Route::middleware(HomeMiddleware::class)->post('/brand',[AdminController::class,'brand']);
});
require __DIR__ . "/front.php";
