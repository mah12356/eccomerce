@php
use App\Models\Brand;
    $nav = Brand::all();
@endphp

<div class="offcanvas-menu-overlay"></div>
<div class="offcanvas-menu-wrapper">
    <div class="offcanvas__option">
    </div>
    <div class="offcanvas__nav__option">
        <a href="#" class="search-switch"><img src="img/icon/search.png" alt=""></a>
    </div>
    <div id="mobile-menu-wrap"></div>
</div>
<!-- Offcanvas Menu End -->

<!-- Header Section Begin -->
<header class="header">

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-1 col-md-3">
                <div class="header__nav__option">
                    <a href="#" class="search-switch"><img src="img/icon/search.png" alt=""></a>
                </div>
            </div>
            <div class="col-lg-8 col-md-6">
                <nav class="header__menu mobile-menu">
                    <ul>
                        <li class=""><a href="/">تماس با ما</a></li>
                        <li><a href="#">درباره ما</a>

                        </li>
                        <li><a href="/brands"><i class="bi bi-arrow-down"></i>کالا ها</a>

                            <ul class="dropdown">
                                @foreach($nav as $item)
                                    <li><a href="/articles/{{$item['id']}}">{{$item['title']}}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        <li><a href="/">خانه</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3 col-md-3 text-center">
                <div class="header__logo">
                    <a href="/"><img src="img/logo.png" alt=""></a>
                </div>
            </div>
        </div>
        <div class="canvas__open"><i class="bi bi-list"></i></div>
    </div>
</header>
