$(function () {
    const countImage = $('.swiper__container').data('count');

    $('select').change(function (e) {
        e.preventDefault();
        let url = $('option').data('url')
        url = url.replace(1, e.target.value);
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (resp) {
                $('.swiper-slide').replaceWith(resp);
                galleryThumbs.params.slidesPerView = $('.swiper__container').data('count');
                galleryThumbs.slideTo(4);
                galleryTop.slideTo(4);
                galleryThumbs.update();
                galleryTop.update();
            }
        });
    });
    var galleryThumbs = new Swiper(".gallery-thumbs", {
        loop: false,
        spaceBetween: 10,
        slidesPerView: countImage,
        freeMode: false,
        watchSlidesProgress: true,
        maxBackfaceHiddenSlides: 1,
        observer: true,
        observeParents: true,
    });
    var galleryTop = new Swiper(".gallery-top", {
        loop: false,
        spaceBetween: 10,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: galleryThumbs,
        },
    });
});
