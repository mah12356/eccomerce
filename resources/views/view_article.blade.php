@php
/* @var App\Http\Controllers\FrontController $model */
/* @var App\Http\Controllers\FrontController $brand */
//echo '<pre>';
//print_r($model);
//exit()
@endphp
<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{asset('css/elegant-icons.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <style>
        p {
            text-align: justify;
        }
        h2{text-align: start}
        img{
            max-width: 600px;
            min-width: 400px;
        }
        article{
            border:2px solid purple;
        }
        .crumb {
            display: flex;
            gap: 0.5rem;
            list-style: none;
        }

        .breadcrumb-link a {
            color: #2196f3;
        }

        .breadcrumb-link a:not(:hover) {
            text-decoration: none;
        }

        .breadcrumb-link:has(~ .breadcrumb-link.current)::after {
            content: "/";
            margin-inline-start: 0.5rem;
        }
    </style>
</head>
<body>
<ol class="crumb">
    <li class="breadcrumb-link"><a href="/">خانه</a></li>
    <li class="breadcrumb-link"><a href="/articles/{{$brand['id']}}">{{$brand['title']}}</a></li>
    <li class="breadcrumb-link current">{{$model['title']}}</li>
</ol>


<article class="px-2 pt-3">
    <h2>{{$model['title']}}</h2>
    <div class="text-center"><img src="{{asset('article/'.$model['banner'])}}" alt="{{$model['alt']}}"></div>
    <p>{{$model['introduction']}}</p>
    @foreach($model['para'] as $item)
        <div class="text-center"><img src="{{asset('para/'.$item['banner'])}}" alt="{{$item['alt']}}"></div>
        {!! $item['paragraph'] !!}
    @endforeach
</article>
</body>
</html>
