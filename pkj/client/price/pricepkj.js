$(function () {
    price_upload();
});
ons.ready(function () {
    price_upload();
});
function price_upload() {
    $("*[type='money']").each(function (i, e) {
        try {
            e = $(this);
            if (e.prop("tagName") === "ONS-INPUT") {
                e = e.find("input");
            }
            e.priceFormat({
                prefix: 'R$ ',
                centsSeparator: ',',
                thousandsSeparator: '.'
            });
            e.attr("type", "tel");

        } catch (e) {
            alert(e.message);
        }
    });
}