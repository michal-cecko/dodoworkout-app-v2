import Swiper from 'https://cdn.jsdelivr.net/npm/swiper@11.2.6/+esm'

const swiper = new Swiper('#workshops-swiper', {
    slidesPerView: 1,
    loop: true,
    navigation: {
        nextEl: '#workshop-swiper-next',
        prevEl: '#workshop-swiper-prev'
    },
    breakpoints: {
        '768': {
            slidesPerView: 2,
        },
        '1280': {
            slidesPerView: 3,
        }
    }
})
