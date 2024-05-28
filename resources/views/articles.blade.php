@php
/** @var $model App\Http\Controllers\FrontController */
// echo '<pre>';
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
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{asset('css/elegant-icons.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <title>Document</title>
    <style>
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            list-style: none;
        }

        .breadcrumb-item a {
            color: #2196f3;
        }

        .breadcrumb-item a:not(:hover) {
            text-decoration: none;
        }

        .breadcrumb-item:has(~ .breadcrumb-item.current)::after {
            content: "/";
            margin-inline-start: 0.5rem;
        }

        body {
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 2rem;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
            color: #333;
            font-family: 'Roboto', sans-serif;
            background-color: #eee;
        }
    </style>
</head>
<body>
@include('includeHtmls.header')
<ol class="crumb">
    <li class="breadcrumb-link"><a href="/">خانه</a></li>
    <li class="breadcrumb-link current">{{$array[0]}}</li>
</ol>
<div class="accordion mx-auto">
    <div class="accordion-item">
        <div class="accordion-item-header ">
            <span class="accordion-item-header-title">فیلتر ها</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down accordion-item-header-icon">
                <path d="m6 9 6 6 6-6" />
            </svg>
        </div>
        <div class="accordion-item-description-wrapper">
            <div class="accordion-item-description">
                <form class="mb-3" method="get">
                    <label for="">جنس کف</label>
                    <input required type="text" name="floor_good" id="">
                    <button class="btn btn-success">save</button>
                </form>
                <form class="mb-3" method="get">
                    <label for="">جنس پارچه</label>
                    <input required type="text" name="fabric_good" id="">
                    <button class="btn btn-success">save</button>
                </form>
                <form class="mb-3" method="get">
                    <label for="">مدل</label>
                    <input required type="text" name="brand" id="">
                    <button class="btn btn-success">save</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <section class="blog spad">
        <div class="container">
            <div class="row">
                @if($get == null)
                    @foreach($model as $item)
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="blog__item">
                                <div class="blog__item__pic set-bg" data-setbg="{{asset('article/'.$item['banner'])}}"></div>
                                <div class="blog__item__text">
                                    <span><img src="article/{{$item['banner']}}" alt="">1302/12/21</span>
                                    <h5>{{$item['title']}}</h5>
                                    <a href="articles/view-article/{{$item['id']}}">بیشتر</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif()
            </div>
        </div>
    </section>
@include('includeHtmls.footer')
<script>
    document.querySelectorAll(".accordion-item").forEach((item) => {
        item.querySelector(".accordion-item-header").addEventListener("click", () => {
            item.classList.toggle("open");
        });
    });
</script>
</body>
</html>
