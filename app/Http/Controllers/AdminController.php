<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Brand;
use App\Models\Para;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AdminController extends Controller
{
    function all_article($id)
    {
        $model = Article::all();
        return \view('admin.insert_article',['model'=>$model]);
    }
    function article($id,Request $request){
        $validate = $request->validate([
            'title' =>'required'

        ]);
        $file = $request->file('banner');
        $file->move(public_path('article'),$file->getClientOriginalName());
        $article=new Article();
        $article->title=$request->input('title');
        $article->banner= $file->getClientOriginalName();
        $article->alt=$request->input('alt');
        $article->introduction=$request->input('introduction');
        $article->category_id=$id;
        $article->floor_good =$request->input('floor_good');
        $article->fabric_good =$request->input('fabric_good');
        $article->width =$request->input('width');
        $article->height =$request->input('height');
        $article->number =$request->input('number');
        $article->save();
    }

    function para($id,Request $request){
        $file = $request->file('image');
        $file->move(public_path('para'),$file->getClientOriginalName());
        $article=new Para();
        $article->banner= $file->getClientOriginalName();
        $article->alt=$request->input('alt');
        $article->paragraph=$request->input('text');
        $article->article_id=$id;
        $article->save();
    }

    function brand(Request $request){
        $article=new Brand();
        $article->title=$request->input('title');
        $article->save();
    }
    function all_brands(){
        $model = Brand::all();
        return view('admin.brand',['model'=>$model]);
    }
}
