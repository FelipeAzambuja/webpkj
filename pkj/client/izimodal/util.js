
function popup(mensagem, title) {  
    try {
        $("#pkjpopup").iziModal('destroy');
    } catch (e) {

    } finally {

    }
    $("#pkjpopup").remove();
    $('body').append("<div id='pkjpopup'>" + mensagem + " </div>");
    $("#pkjpopup").iziModal({
        'title':title
    });
    $("#pkjpopup" ).iziModal('open');
}
function popup_close() {
    try {
        $("#pkjpopup").iziModal('close');
    } catch (e) {

    } finally {

    }
}
