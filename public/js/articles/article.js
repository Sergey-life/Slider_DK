$(function () {
    $('.js-checkbox').change(function (e) {
        e.preventDefault();
        let form = $('.js-form');
        let url = form.attr('action');
        $.ajax({
            url: url,
            type: 'GET',
            data: form.serialize(),
            success: function (resp) {
                console.log(resp);
            }
        });
    });
});
