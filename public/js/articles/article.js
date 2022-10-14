$(function () {
    $(document).change('.js-checkbox' ,function (e) {
        e.preventDefault();
        let form = $('.js-form');
        let url = form.attr('action');
        $.ajax({
            url: url,
            type: 'GET',
            data: form.serialize(),
            success: function (resp) {
                // $('input').find(`[value=${$(this).data('tags')}]`).prop('checked', true);
                $('.js-articles').replaceWith(resp.html);
                console.log(form)
            }
        });
    });
});
