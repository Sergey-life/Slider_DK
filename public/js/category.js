$(function () {
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
                swiper.update();
                swiper2.update();
                // for (i = 0; i < resp.length; ++i) {
                //     console.log(resp[i].image);
                //     obj.replaceWith(`<div class="swiper-slide"><img src="${resp[1].image}"></div>`);
                //     objTwo.replaceWith(`<div class="swiper-slide"><img src="${resp[2].image}"></div>`);
                // }
                // resp.forEach(function (el) {
                //     console.log(el);

                // });
                    // $('.swiper-slide').replaceWith(`<div class="swiper-slide"><img src="${resp[i].image}"></div>`);
                // data.forEach(function (el) {
                //     console.log(el.image);
                //     $('.swiper-slide img').attr('src', el.image)
                // });
                // $('.swiper-slide img').attr('src', data.image)
            }
        });
    });
    var swiper = new Swiper(".mySwiper", {
        loop: true,
        spaceBetween: 10,
        slidesPerView: 3,
        freeMode: true,
        watchSlidesProgress: true,
        maxBackfaceHiddenSlides: 1
    });
    var swiper2 = new Swiper(".mySwiper2", {
        loop: true,
        spaceBetween: 10,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: swiper,
        },
    });
});
