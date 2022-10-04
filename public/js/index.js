$(function () {
    const obj = $("#addupdatepopup");
    obj.on('show.bs.modal', function (e) { // reste the form on open of modal id- show.bs.modal
    obj.find("[name='title']").val('');
    obj.find("[name='product_code']").val('');
    obj.find("[name='id']").val('');
    obj.find("[name='image']").val(null);
    obj.find("#prview").css('visibility', 'hidden');
    obj.find("#prview").attr('src', '');

    clearCheckbox();
});
    $(document).on("click", ".edit-product", function (e) { // on click of edit button prefilled the form
    e.preventDefault();
    let product = $(this).data("json");
    let categories = $(this).data("categories");
    obj.modal('show');
    obj.find("[name='title']").val(product.title);
    obj.find("[name='product_code']").val(product.product_code);
    obj.find("[name='id']").val(product.id);
    obj.find("#prview").css('visibility', 'visible');
    obj.find("#prview").attr('src', product.image);

    for (let category of categories) {
    obj.find(`[value=${category.id}]`).prop('checked', true);
}
    $('#btnClose').click(function (e) {
        e.preventDefault();
        obj.modal('hide');
    });

    return false;
});
    $(document).on('submit', ".formsubmit", function (e) {
    e.preventDefault();
    let action = $(this).attr('action');
    let method = $(this).attr('method');
    let formData = new FormData(this);

    callAjax(action, method, formData);
});
    $(document).on("click", "#pagination a,#search_btn", function (e) {
    //get url and make final url for ajax
    let url = $(this).attr("href");
    let append = url.indexOf("?") == -1 ? "?" : "&";
    let finalURL = url + append + $("#searchform").serialize();

    //set to current url
    window.history.pushState({}, null, finalURL);

    getData(finalURL);

    return false;
});
    function callAjax(action, method, formData) {
    $.ajax({
    url: action,
    type: method,
    data: formData,
    success: function (data) {
    $(".alert").remove();
    obj.modal('hide');
    getData($("#search_btn").attr('href'));
    clearCheckbox();
    alert(data.message);
},
    error: function (response) {
    // set the error messages
    $(".alert").remove();
    let errorJson = JSON.parse(response.responseText);
    for (let err in errorJson) {
    for (let errStr of errorJson[err]){
        console.log(err);
        console.log(errorJson[err]);
        obj.find("[name='" + err +'[]' +"'").after("<div class='alert alert-danger'>" + errorJson[err] + "</div>");
    }
}
},
    cache: false,
    contentType: false,
    processData: false
});
}
    function getData(url) {
    $.get(url, function (data) {
        $("#pagination_data").html(data);
    });
}
    function clearCheckbox() {
    $('#addupdatepopup input').prop('checked', false);
}
});
