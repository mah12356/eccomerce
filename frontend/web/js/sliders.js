let swiperSlider = new Swiper(".slider", {
    slidesPerView: 3,
    spaceBetween: 0,
    breakpoints: {
        // when window width is >= 320px
        320: {
            slidesPerView: 1,
            spaceBetween: 20
        },
        // when window width is >= 480px
        500: {
            slidesPerView: 1,
            spaceBetween: 30
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 0
        },
    },
    // freeMode: true,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    mousewheel: true,
    keyboard: true,
});
let swiperCarousel = new Swiper(".carousel", {
    slidesPerView: 3,
    spaceBetween: 30,
    breakpoints: {
        // when window width is >= 320px
        320: {
            slidesPerView: 1,
            spaceBetween: 20
        },
        // when window width is >= 480px
        500: {
            slidesPerView: 1,
            spaceBetween: 30
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 30,
        },
        1420: {
            slidesPerView: 4,
            spaceBetween: 15,
        }
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
});
let swiperRelated = new Swiper(".related", {
    slidesPerView: 5,
    spaceBetween: 30,
    breakpoints: {
        // when window width is >= 320px
        320: {
            slidesPerView: 1,
            spaceBetween: 20
        },
        // when window width is >= 480px
        530: {
            slidesPerView: 2,
            spaceBetween: 10
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 15,
        },
        1420: {
            slidesPerView: 5,
            spaceBetween: 15,
        }
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
});