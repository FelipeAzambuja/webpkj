function popup(mensagem, id) {
    if (id === undefined) {
        id = "";
    }
    $("#pkjpopup" + id).remove();
    $('body').append("<div id='pkjpopup" + id + "'><a class='b-close'>x<a/> " + mensagem + " </div>");
    $("#pkjpopup" + id).bPopup({
        speed: 0,
        positionStyle: "absolute",
        onClose: function () {
            $(".b-modal").remove();
            $("#pkjpopup" + id).remove();
        }
    });
}
function popup_close(id){
    if (id === undefined) {
        id = "";
    }
    $("#pkjpopup" + id+ " > .b-close").trigger('click');
}
