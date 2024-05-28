<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/articles',[FrontController::class,'articles']);
Route::get('/articles/view-article/{id}',[FrontController::class,'view_article']);
Route::get('/a',[FrontController::class,'a']);
