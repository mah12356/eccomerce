<?php

use Illuminate\Http\Request;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
Route::post('/hello',function (Request $request){
    return response()->json($request);
//dd($request);
//header('Access-Control-Allow-Origin:*');
//$post = new Profile();
//$post->user_id = $request->input('user_id');
//$post->name = $request->input('name');
//$post->save();
//     if ($request->isMethod('POST')) {
//         return response()->json([
//             'data'=>'wqeewqwqe'
//         ]);
//     }
});
