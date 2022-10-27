$(function () {
    $(document).change('.js-checkbox' ,function (e) {
        e.preventDefault();
        let form = $('.js-form');
        let url = form.attr('action');
        $.ajax({
            url: url,
            type: 'GET',
            data: form.serialize(),
            // formData: 'html',
            success: function (resp) {
                $('.js-articles').replaceWith(resp.html);
                console.log(resp.articles);
                console.log(resp.topics);
                console.log(resp.tags);
            }
        });
    });
});
