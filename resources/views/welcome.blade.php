<!DOCTYPE html>
<html lang="fa">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>مبلمان ایپک</title>
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="{{asset('css/elegant-icons.css')}}">
        <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/style.css')}}">
    </head>
    <body>
   @include('includeHtmls.header')
    <section class="hero">
        <div class="hero__slider owl-carousel">
            <div class="hero__items set-bg" data-setbg="{{asset('img/_storage_emulated_0_Android_data_app.rbmain.a_cache_-2147483648_-210055.jpg')}}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                <h2 class="text-white">بهتری کار های ما</h2>
                                <p class="text-white">طراحی با ظرافت بالا</p>
{{--                                <a href="#" class="primary-btn">همین الان خرید کن<span class="bi bi-arrow-right"></span></a>--}}
                                <div class="hero__social">
                                    <a href="#"><i class="bi text-white bi-facebook"></i></a>
                                    <a href="#"><i class="bi text-white bi-twitter"></i></a>
                                    <a href="#"><i class="bi text-white bi-pinterest"></i></a>
                                    <a href="#"><i class="bi text-white bi-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero__items set-bg" data-setbg="{{asset('img/_storage_emulated_0_Android_data_app.rbmain.a_cache_-2147483648_-210057.jpg')}}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                <h6></h6>
                                <h2 class="text-white">بهتری کار های ما</h2>
                                <p class="text-white">طراحی با ظرافت بالا</p>
{{--                                <a href="#" class="primary-btn">Shop now <span class="arrow_right"></span></a>--}}
                                <div class="hero__social">
                                    <a href="#"><i class="bi bi-facebook"></i></a>
                                    <a href="#"><i class="bi bi-twitter"></i></a>
                                    <a href="#"><i class="bi bi-pinterest"></i></a>
                                    <a href="#"><i class="bi bi-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Banner Section Begin -->
    <section class="banner spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 offset-lg-4">
                    <div class="banner__item">
                        <div class="banner__item__pic">
                            <img src="{{asset('img/_storage_emulated_0_Android_data_app.rbmain.a_cache_-2147483648_-210057.jpg')}}" alt="">
                        </div>
                        <div class="banner__item__text">
                            <h2>مدل لباس های سال 2023</h2>
                            <a href="#">همین الان خرید کن</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="banner__item banner__item--middle">
                        <div class="banner__item__pic">
                            <img src="{{asset('img/988866fa008cf4f9647416f86871fea4699062d3_1603188830.jpg')}}" alt="">
                        </div>
                        <div class="banner__item__text">
                            <h2>مدل های دیگر</h2>
                            <a href="#">همین الان خرید کن</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="banner__item banner__item--last">
                        <div class="banner__item__pic">
                            <img src="{{asset('img/5fe6bd620212e0006f871cc4c7ed47d4d0dae20d_1666439455.jpg')}}" alt="">
                        </div>
                        <div class="banner__item__text">
                            <h2>کفش های بهار 2023</h2>
                            <a href="#">همین الان خرید کن</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Section End -->

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="filter__controls">
                        <li class="active" data-filter="*">بیشترین فروش</li>
                        <li data-filter=".new-arrivals">تازه های وارد شده به بازار</li>
                        <li data-filter=".hot-sales">داغترین فروش ها</li>
                    </ul>
                </div>
            </div>
            <div class="row product__filter">
                <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix new-arrivals">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="img/d7641d59-cf37-4ddf-9a0f-e2dfe373ecae.jpg">
                            <span class="label">جدید</span>
                            <ul class="product__hover">
                                <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                                <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>سنجش</span></a></li>
                                <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>امدل اسپرت</h6>
                            <!-- <a href="#" class="add-cart">+اضافه به کارت</a> -->
                            <div class="rating">
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                            </div>
                            <!-- <h5>1.000.000 T</h5> -->
                            <div class="product__color__select">
                                <label for="pc-1">
                                    <input type="radio" id="pc-1">
                                </label>
                                <label class="active black" for="pc-2">
                                    <input type="radio" id="pc-2">
                                </label>
                                <label class="grey" for="pc-3">
                                    <input type="radio" id="pc-3">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix hot-sales">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="img/Untitled-2-copy-218.jpg">
                            <ul class="product__hover">
                                <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                                <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>سنجش</span></a></li>
                                <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>مدل اسپرت</h6>
                            <!-- <a href="#" class="add-cart">+ اضافه به کارت</a> -->
                            <div class="rating">
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                            </div>
                            <!-- <h5>1.000.000 T</h5> -->
                            <div class="product__color__select">
                                <label for="pc-4">
                                    <input type="radio" id="pc-4">
                                </label>
                                <label class="active black" for="pc-5">
                                    <input type="radio" id="pc-5">
                                </label>
                                <label class="grey" for="pc-6">
                                    <input type="radio" id="pc-6">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix new-arrivals">
                    <div class="product__item sale">
                        <div class="product__item__pic set-bg" data-setbg="img/mahor-550x550.jpg">
                            <span class="label">فروش</span>
                            <ul class="product__hover">
                                <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                                <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>سنجش</span></a></li>
                                <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>چرم اسپرت</h6>
                            <!-- <a href="#" class="add-cart">+ اضافه به کارت</a> -->
                            <div class="rating">
                                <i class="bi bi-star"></i>
                                <i class="bi bi-star"></i>
                                <i class="bi bi-star"></i>
                                <i class="bi bi-star"></i>
                                <i class="bi bi-star-o"></i>
                            </div>
                            <!-- <h5>1.000.000 T</h5> -->
                            <div class="product__color__select">
                                <label for="pc-7">
                                    <input type="radio" id="pc-7">
                                </label>
                                <label class="active black" for="pc-8">
                                    <input type="radio" id="pc-8">
                                </label>
                                <label class="grey" for="pc-9">
                                    <input type="radio" id="pc-9">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix hot-sales">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="img/mbl-rahty-7-nfrh-shana.jpg">
                            <ul class="product__hover">
                                <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                                <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>سنجش</span></a></li>
                                <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>اسپرت پیاده روی</h6>
                            <!-- <a href="#" class="add-cart">+ اضافه به کارت</a> -->
                            <div class="rating">
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                            </div>
                            <!-- <h5>1.000.000 T</h5> -->
                            <div class="product__color__select">
                                <label for="pc-10">
                                    <input type="radio" id="pc-10">
                                </label>
                                <label class="active black" for="pc-11">
                                    <input type="radio" id="pc-11">
                                </label>
                                <label class="grey" for="pc-12">
                                    <input type="radio" id="pc-12">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix new-arrivals">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="img/مبل-راحتی-7-نفره-دیانا-1-1024x837.jpg">
                            <ul class="product__hover">
                                <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                                <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>سنجش</span></a></li>
                                <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>اسپرت ساده</h6>
                            <!-- <a href="#" class="add-cart">+ اضافه به کارت</a> -->
                            <div class="rating">
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                            </div>
                            <!-- <h5>1.000.000 T</h5> -->
                            <div class="product__color__select">
                                <label for="pc-13">
                                    <input type="radio" id="pc-13">
                                </label>
                                <label class="active black" for="pc-14">
                                    <input type="radio" id="pc-14">
                                </label>
                                <label class="grey" for="pc-15">
                                    <input type="radio" id="pc-15">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix hot-sales">
                    <div class="product__item sale">
                        <div class="product__item__pic set-bg" data-setbg="img/280x280.jpg">
                            <span class="label">فروش</span>
                            <ul class="product__hover">
                                <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                                <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>سنجش</span></a></li>
                                <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>چرم خالص</h6>
                            <!-- <a href="#" class="add-cart">+ اضافه به کارت</a> -->
                            <div class="rating">
                                <i class="bi bi-star"></i>
                                <i class="bi bi-star"></i>
                                <i class="bi bi-star"></i>
                                <i class="bi bi-star"></i>
                                <i class="bi bi-star-o"></i>
                            </div>
                            <!-- <h5>1.000.000 T</h5> -->
                            <div class="product__color__select">
                                <label for="pc-16">
                                    <input type="radio" id="pc-16">
                                </label>
                                <label class="active black" for="pc-17">
                                    <input type="radio" id="pc-17">
                                </label>
                                <label class="grey" for="pc-18">
                                    <input type="radio" id="pc-18">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix new-arrivals">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="img/_storage_emulated_0_Android_data_app.rbmain.a_cache_-2147483648_-210068.jpg">
                            <ul class="product__hover">
                                <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                                <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>سنجش</span></a></li>
                                <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>اسپرت مدل دار</h6>
                            <!-- <a href="#" class="add-cart">+ اضافه به کارت</a> -->
                            <div class="rating">
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                            </div>
                            <div class="product__color__select">
                                <label for="pc-19">
                                    <input type="radio" id="pc-19">
                                </label>
                                <label class="active black" for="pc-20">
                                    <input type="radio" id="pc-20">
                                </label>
                                <label class="grey" for="pc-21">
                                    <input type="radio" id="pc-21">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix hot-sales">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="img/_storage_emulated_0_Android_data_app.rbmain.a_cache_-2147483648_-210073.jpg">
                            <ul class="product__hover">
                                <li><a href="#"><img src="img/icon/heart.png" alt=""></a></li>
                                <li><a href="#"><img src="img/icon/compare.png" alt=""> <span>سنجش</span></a></li>
                                <li><a href="#"><img src="img/icon/search.png" alt=""></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>چرم اسپرت</h6>
                            <!-- <a href="#" class="add-cart">+ اضافه به کارت</a> -->
                            <div class="rating">
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                                <i class="bi bi-star-o"></i>
                            </div>
                            <!-- <h5>1.000.000 T</h5> -->
                            <div class="product__color__select">
                                <label for="pc-22">
                                    <input type="radio" id="pc-22">
                                </label>
                                <label class="active black" for="pc-23">
                                    <input type="radio" id="pc-23">
                                </label>
                                <label class="grey" for="pc-24">
                                    <input type="radio" id="pc-24">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->

    <!-- Categories Section Begin -->
    <section class="categories spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="categories__text">
                        <h2>کفش های گرم<br /> <span>همه کفش ها</span> <br /> لوازم متفرقه</h2>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="categories__hot__deal">
                        <img src="{{asset('img/b2ab8477deb5c78ee934fbdee3e4c0d06bdc88f0_1627914414.jpg')}}" alt="">
                        <div class="hot__deal__sticker">
                            <span>فروش</span>
                            <!-- <h5>1.000.000 T</h5> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 offset-lg-1">
                    <div class="categories__deal__countdown">
                        <span>فروش های هفته</span>
                        <h2>فرصت باقی مانده تا پایان تخفیف</h2>
                        <div class="categories__deal__countdown__timer" id="countdown">
                            <div class="cd-item">
                                <span>3</span>
                                <p>روز</p>
                            </div>
                            <div class="cd-item">
                                <span>1</span>
                                <p>ساعت</p>
                            </div>
                            <div class="cd-item">
                                <span>50</span>
                                <p>دقیقه</p>
                            </div>
                            <div class="cd-item">
                                <span>18</span>
                                <p>ثانیه</p>
                            </div>
                        </div>
                        <a href="#" class="primary-btn">همین الان خرین کن</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Categories Section End -->

    <!-- Instagram Section Begin -->
    <section class="instagram spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="instagram__pic">
                        <div class="instagram__pic__item set-bg" data-setbg="{{asset('img/IMG-20220522-WA0019.jpg')}}"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="{{asset('img/khryd-mbl-rahty-az-tolydy.jpg')}}"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="{{asset('img/d93e79a5-abef-485c-8d6b-4e3118a7b1d1.jpg')}}"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="{{asset('img/ece72bf8b28fe2b1bc61186fae0960e9cb1cf02c_1634459974.jpg')}}"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="{{asset('img/d93e79a5-abef-485c-8d6b-4e3118a7b1d1.jpg')}}"></div>
                        <div class="instagram__pic__item set-bg" data-setbg="{{asset('img/مبل-راحتی-هفت-نفره-کد-0520-دکورابو.jpg')}}"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="instagram__text">
                        <h2>اینستاگرام</h2>
                        <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است </p>
                        <h3>مدل های مردانه</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Instagram Section End -->

    <!-- Latest Blog Section Begin -->
    <section class="latest spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>اخرین اخبار</span>
                        <h2>فشن های ترند شده</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic set-bg" data-setbg="img/5fe6bd620212e0006f871cc4c7ed47d4d0dae20d_1666439455.jpg"></div>
                        <div class="blog__item__text">
                            <span><img src="img/icon/calendar.png" alt="">1400/12/5</span>
                            <h5>کدام یک از کفش ها بهترین اند</h5>
                            <a href="#">بیشتر</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic set-bg" data-setbg="img/_storage_emulated_0_Android_data_app.rbmain.a_cache_-2147483648_-210073.jpg"></div>
                        <div class="blog__item__text">
                            <span><img src="img/icon/calendar.png" alt="">1400/11/4</span>
                            <h5>برند های ماندگار</h5>
                            <a href="#">بیشتر</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic set-bg" data-setbg="img/3781058.jpg"></div>
                        <div class="blog__item__text">
                            <span><img src="img/icon/calendar.png" alt="">1399/3/12</span>
                            <h5>تاثیر کفش بروی سلامتی</h5>
                            <a href="#">بیشتر</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
   @include('includeHtmls.footer')
    <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('js/jquery.nice-select.min.js')}}"></script>
    <script src="{{asset('js/jquery.nicescroll.min.js')}}"></script>
    <script src="{{asset('js/jquery.countdown.min.js')}}"></script>
    <script src="{{asset('js/jquery.slicknav.js')}}"></script>
    <script src="{{asset('js/mixitup.min.js')}}"></script>
    <script src="{{asset('js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('js/main.js')}}"></script>
    </body>
</html>
