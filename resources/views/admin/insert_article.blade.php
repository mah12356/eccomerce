<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/sidebar.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('css/sidebar.css')}}">
</head>
<body>

<form action="{{ route('voyager.logout') }}" method="POST">
    {{ csrf_field() }}
    <button type="submit" class="btn btn-danger btn-block">
        @if(isset($item['icon_class']) && !empty($item['icon_class']))
            <i class="{!! $item['icon_class'] !!}"></i>
        @endif
        خروج
    </button>
</form>

<form method="post" enctype="multipart/form-data">
    @csrf
    <div class="input-group mb-3">
        <input type="text" name="title" class="form-control" placeholder="title" aria-label="Username" aria-describedby="basic-addon1">
    </div>

    <div class="input-group mb-3">
        <input type="text" name="floor_good" class="form-control" placeholder="جنس کف">
    </div>

    <div class="input-group mb-3">
        <input type="number" name="width" class="form-control" placeholder="طول" aria-label="Username" aria-describedby="basic-addon1">
    </div>

    <div class="input-group mb-3">
        <input type="number" name="height" class="form-control" placeholder="عرض" aria-label="Username" aria-describedby="basic-addon1">
    </div>

    <div class="input-group mb-3">
        <input type="number" name="number" class="form-control" placeholder="تعداد آدم" aria-label="Username" aria-describedby="basic-addon1">
    </div>

    <div class="input-group mb-3">
        <input type="text" name="fabric_good" class="form-control" placeholder="جنس پارچه" aria-label="Username" aria-describedby="basic-addon1">
    </div>

    <div class="input-group mb-3">
        <input type="text" name="meta" class="form-control" placeholder="متا" aria-label="Recipient's username" aria-describedby="basic-addon2">
    </div>

    <div class="input-group mb-3">
        <input type="file" placeholder="بنر" name="banner" class="form-control" id="basic-url" aria-describedby="basic-addon3">
    </div>

    <textarea name="" id="text" cols="17" rows="6"></textarea>

    <div class="input-group mb-3">
        <input type="text" name="alt" class="form-control" placeholder="alt" aria-label="">
    </div>
    <button class="btn btn-success">save</button>
</form>

<section class="row mt-5">
    @foreach($model as $item)
        <div class="card" style="width: 18rem;">
            <img src="{{asset('article/'.$item['banner'])}}" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                <a href="/admin/insert-para/{{$item['id']}}" class="btn btn-primary">{{$item['title']}}</a>
            </div>
        </div>
    @endforeach
</section>

@include('includeHtmls.sidebar')
<script src="https://cdn.tiny.cloud/1/kzpya4dt62k0cgolnyzjc1l03z8la4gczrcgh0irny26u6ok/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>
    tinymce.init({
        selector: 'textarea#text',
        language:'fa',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount linkchecker',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
</script>
</body>
</html>
