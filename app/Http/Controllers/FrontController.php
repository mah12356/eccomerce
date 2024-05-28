<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Brand;
use Illuminate\Http\Request;
use yii\helpers\Url;

class FrontController extends Controller
{
    function articles(){
        if ($_GET == null){
            $model = Article::all()->toArray();
        }else{
            $array=[];
            foreach ($_GET as $y=> $x){
                array_push($array,$y);
            }
            $model = Article::where([$array[0]=>$_GET[$array[0]]])->get()->toArray();
        }
        return view('articles',[
            'model'=>$model,
            'get'=>$array
        ]);
    }
    function view_article($id){

        $model = Article::with('para')->find($id)->toArray();
        $brand = Brand::find($model['category_id'])->toArray();
        return view('view_article',
            ['model'=>$model,
             'brand'=>$brand
            ]);
    }

    /**
     * @throws \SoapFault
     */
    function a(){
        $options = [
            'cache_wsdl'=>0,
            'trace'=>1,
            'stream_context'=>stream_context_create(['ssl'=>[
                'verify_peer'=>false,
                'verify_peer_name'=>false,
                'allow_self_signed'=>true
            ]])
        ];
        $CallbackURL = '127.0.0.1:8000/payment-result'; // Required
        $send = new \SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', $options);
        $result = $send->PaymentRequest(
            [
                'MerchantID' => '2e0a599a-bc50-46c2-84db-0f2acaf0e3a3',
                'Amount' => 30000,
                'Description' => 'jebfgvejh',
                'CallbackURL' => $CallbackURL
            ]
        );
        if ($result->Status == 100 || $result->Status == 101){
            return redirect('https://www.zarinpal.com/pg/StartPay/'. $result->Authority);
        }else{
            echo 'error';
            exit();
        }
    }
}
