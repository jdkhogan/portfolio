const swipers = document.querySelectorAll('.swiper');
if (swipers) {
    Object.values(swipers).map(swiper => {
        const carousel = new Swiper(swiper, {
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    });
}