<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/sidebar.css')}}">
    <title>اضافه کردن برند</title>
    <style>
        div{
            height: 200px;
        }
    </style>
</head>
<body>
<form method="post">
    @csrf
    <input type="text" placeholder="نام برند" name="title">
    <button class="btn btn-success">save</button>
</form>
<div class="pt-5">
    @foreach($model as $item)
        <a href="insert-article/{{$item['id']}}">{{$item['title']}}</a>
    @endforeach
</div>
@include('includeHtmls.sidebar')
</body>
</html>
