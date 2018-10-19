function alert(mensagem, done, args) {
    $.confirm({
        title: false,
        content: mensagem,
        escapeKey: 'Ok',
        buttons: {
            'Ok': function () {

                if (typeof done !== 'undefined') {
                    if (typeof bind === 'undefined') {
                        done();
                    } else {

                        bindCall('', done, args);
                    }
                }
            }
        }
    });
}
function confirm(mensagem, done, botoes) {
    var btns = {};
    $.each(botoes, function (i, e) {
        if (Object.keys(e).length === 1) {
            btns[e[0]] = {
                text: e[0],
                action: function () {
                    bindCall('', done, { 'btn': e[0] });
                }
            }
        } else if (Object.keys(e).length === 2) {
            btns[e[0]] = {
                text: e[0],
                btnClass: 'btn-'+e[1],
                action: function () {
                    bindCall('', done, { 'btn': e[0] });
                }
            }
        } else if (Object.keys(e).length === 3) {
            btns[e[0]] = {
                text: e[0],
                btnClass: 'btn-'+e[1],
                keys:Object.values(e[2]),
                action: function () {
                    bindCall('', done, { 'btn': e[0] });
                }
            }
        } else {
            btns[e[0]] = {
                text: 'Ok',
                action: function () {
                    bindCall('', done, { 'btn': 'Ok' });
                }
            }
        }

    });
    $.confirm({
        title: false,
        content: mensagem,
        buttons: btns
    });
}