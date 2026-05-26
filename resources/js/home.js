import Swiper from 'https://cdn.jsdelivr.net/npm/swiper@11.2.6/+esm'
import { Navigation, Autoplay } from 'https://cdn.jsdelivr.net/npm/swiper@11.2.6/modules/+esm'

const heroSwiper = new Swiper('#hero-swiper', {
    modules: [Navigation, Autoplay],
    slidesPerView: 1,
    spaceBetween: 60,
    navigation: {
        nextEl: '#hero-swiper-next',
        prevEl: '#hero-swiper-prev'
    },
    allowTouchMove: true,
    speed: 800,
    autoplay: {
        delay: 7000
    },
    breakpoints: {
        '768': {
            allowTouchMove: false,
        },
    },
    on: {
        init: function () {
            document.getElementById('hero-swiper-total-pages').textContent =
                this.slides.length
            document.getElementById('hero-swiper-current-page').textContent =
                this.activeIndex + 1
        },
        realIndexChange: function () {
            document.getElementById('hero-swiper-current-page').textContent =
                this.activeIndex + 1
        },
    }
})

const workshopsSwiper = new Swiper('#workshops-swiper', {
    modules: [Navigation],
    slidesPerView: 1,
    loop: false,
    navigation: {
        nextEl: '#workshop-swiper-next',
        prevEl: '#workshop-swiper-prev'
    },
    breakpoints: {
        '768': {
            slidesPerView: 2,
        },
        '1400': {
            slidesPerView: 2.5,
        },
        '1700': {
            slidesPerView: 3,
        },
        '2100': {
            slidesPerView: 3.5,
        }
    }
})
