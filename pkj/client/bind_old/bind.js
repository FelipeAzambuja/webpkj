var bind_out = "body";
var bind_router = "/pkj/server/pkjall.php";
var bind_default = "";

var eventos = [];
var session = {};
function sisBindInterval(e, tipo) {
    if (e === undefined) {
        return;
    }
    var fun = $(e).attr(tipo);
    if (fun !== undefined) {
        var pagina = $(e).attr("page");
        if (pagina === undefined) {
            pagina = bind_default;
        }
        var sisfunHAppyyyy = formData($(e).parents("form:eq(0),tr:eq(0)"));
        sisfunHAppyyyy.session = session;
        var funName = part(fun, "(")[0];
        var atrStr = part(part(fun, "(")[1], ")")[0];
        var atrs = part(atrStr, ",");
        atrStr = "";
        for (i = 0; i < atrs.length; i++) {
            var v = atrs[i];
            eval("sisfunHAppyyyy.post" + i + ' = "' + v + '";');
        }
        if ($(e).attr("lock") !== undefined) {
            var loading_text = $(e).attr("load-text");
            var orig_text = $(e).val();
            if (typeof loading_text != "undefined") {
                $(e).val(loading_text);
            }
            lock(e);
        }
        sisfunHAppyyyy.CMD = funName;
        sisfunHAppyyyy.PAGE = pagina;
        if (typeof page != "undefined") {
//            sisfunHAppyyyy.MUSTACHE = page.data["home"];
            sisfunHAppyyyy.MUSTACHE = $.extend({}, page.data);
        }
        sisfunHAppyyyy.HOST = window.location.protocol + "//" + window.location.hostname;
        if (bind_router !== "") {
            pagina = bind_router;
        }
        $.ajaxSetup({
            xhrFields: {
                withCredentials: true
            },
            cache: false
        });
        sisfunHAppyyyy.GET = $_GET;
        $.post(pagina, sisfunHAppyyyy, function (resp) {
            if (len(trim(resp)) > 0) {
                try {
                    eval(resp);
                } catch (e) {
                    console.log(resp);
                    console.log(e);
                }
            }
            if ($(e).attr("lock") !== undefined) {
                lock(e);
                var loading_text = $(e).attr("load-text");
                if (typeof loading_text != "undefined") {
                    $(e).val(orig_text);
                }
            }
        }).fail(function (erro) {
            if ($(e).attr("lock") !== undefined) {
                lock(e);
                var loading_text = $(e).attr("load-text");
                if (typeof loading_text != "undefined") {
                    $(e).val(orig_text);
                }
            }
            console.log(erro);
            http = erro.status;
            msg = erro.responseText;
            alert("ERRO " + http + ":" + msg + ":" + pagina);
        })
    }
    return true
}
var bind_runing = false;
function bindRefresh() {
    if (bind_runing) {
        console.log("Aguarde o processamento");
        return;
    }
    bind_runing = true;
    $.each($("ons-button ,ons-range,ons-input,ons-switch,input,select,a,button,img,textarea"), function (e, t) {
        if ($(t).attr("bind") === undefined) {
            var n = "";
            if ($(t).attr("keyup") !== undefined) {
                n = "keyup";
                $(t).attr("on" + n, "sisBindInterval(this, '" + n + "')").attr("bind", true);
            }
            if ($(t).attr("keydown") !== undefined) {
                n = "keydown";
                $(t).attr("on" + n, "sisBindInterval(this, '" + n + "')").attr("bind", true);
            }
            if ($(t).attr("keypress") !== undefined) {
                n = "keypress";
                $(t).attr("on" + n, "sisBindInterval(this, '" + n + "')").attr("bind", true);
            }
            if ($(t).attr("blur") !== undefined) {
                n = "blur";
                $(t).attr("on" + n, "sisBindInterval(this, '" + n + "')").attr("bind", true);
            }
            if ($(t).attr("click") !== undefined) {
                n = "click";
                $(t).attr("on" + n, "sisBindInterval(this, '" + n + "')").attr("bind", true);
                //csp 
//                    $(t).on(n,function(){sisBindInterval(this, '" + n + "')}).attr("bind", true);
            }
            if ($(t).attr("change") !== undefined) {
                n = "change";
                $(t).attr("on" + n, "sisBindInterval(this, '" + n + "')").attr("bind", true);
            }
        }
    });
    $('select').each(function (i, pai) {
        var value = $(pai).attr('value');
        if (value !== undefined) {
            $(pai).find('option').each(function (i, e) {
                if ($(e).val() === value) {
                    $(e).attr('selected', true);
                    $(pai).trigger("chosen:updated").chosen({"width": "100%"});
                }
            })
        }
    });
    bind_runing = false;
}

function bindCall(pagina, funcao, data, done) {
    if (pagina == null) {
        pagina = bind_default;
    }
    if (pagina == "") {
        pagina == bind_default;
    }
    if (data === undefined) {
        data = {};
        data.post0 = "";
    }
    data.CMD = funcao;
    data.PAGE = pagina;
    if (data === {}) {
        data.post0 = "";
    }
    if (data.post0 === undefined) {
        data.post0 = "";
    }
    if (bind_router !== "") {
        pagina = bind_router;
    }
    $.ajaxSetup({
        xhrFields: {
            withCredentials: true
        },
        cache: false
    });
    data.HOST = window.location.protocol + "//" + window.location.hostname;

//    if (typeof page != "undefined") {
//        data.MUSTACHE = page.data;
//    }

    data.GET = $_GET;
    $.post(pagina, data, function (resp) {
        if (len(trim(resp)) > 0) {
            try {
                eval(resp);
            } catch (e) {
                console.log(resp);
                console.log(e);
                console.log(resp.stack);
            }
        }
        if (typeof (done) !== "undefined") {
            done(resp);
        }
    }).fail(function (erro) {
        console.log(erro);
        http = erro.status;
        msg = erro.responseText;
        alert("ERRO " + http + ":" + msg);
    })
}

function formData(formulario) {
    var form = {};
    $(formulario).find(':input').each(function () {
        var self = $(this);
        var id = self.attr('id');
        //espero que n�o de merda :D
        if (id === undefined) {
            return;
        }
        var name = self.attr('name');
        var qtde = 0;
        if (id !== undefined) {
            qtde = $('#' + (id.replace('[]', '')) + "\\[\\]").size();//aeowwww
        }
        var valor = "";
        if (self.attr("type") === "checkbox" || self.attr("type") === "radio") {
            valor = (self.is(":checked")) + "";
            if (typeof (name) !== "undefined") {
                if (self.attr("type") === "radio") {
                    valor = $("input[type='radio'][name='" + name + "']:checked").val();
                    if (valor === undefined) {
                        valor = "false";
                    }
                    var data_id = $($("input[type='radio'][name='" + name + "']:checked").get(0)).attr("data-id");
                    if (data_id !== undefined) {
                        valor = $("input[type='radio'][name='" + name + "']:checked").attr("data-id");
                    }
                }
                //verifica se � um grupo pelo name
                var possivelArray = $("input[name='" + name + "']").toArray();
                //if(possivelArray.length > 1 && self.attr("type") === "checkbox"){
                if (self.attr("type") === "checkbox") {
                    var ferramenta = $("input[type='checkbox'][name='" + name + "']:checked").get();
                    var valor = [];
                    for (var i = 0; i < ferramenta.length; i++) {
                        if ($(ferramenta[i]).attr("data-id") !== undefined) {
                            valor[i] = $(ferramenta[i]).attr("data-id");
                        } else {
                            valor[i] = $(ferramenta[i]).val();
                        }
                    }
                }
            }
        } else {
            if (self.attr("data-id") !== undefined) {
                valor = self.attr("data-id");
            } else {
                valor = self.val();
            }
        }
        if (id !== undefined) {
            //n�o coisar o j� coisado
            if (id.indexOf('[]') !== -1 && typeof (valor) !== "object") {
                id = id.replace('[]', '');
                if (form[id] !== undefined) {
                    form[id] = form[id] + '|pkj|' + valor;
                } else {
                    if (qtde === 1) {
                        form[id] = '|POG||pkj|' + valor;
                    } else {
                        form[id] = valor;
                    }
                }
            } else {
                if (typeof (valor) === "array" || typeof (valor) === "object") {
                    if (valor.length < 1) {
                        delete form[id];
                        id = id.replace('[]', '');
                        form[id] = "false";
                    } else {
                        form[id] = valor;
                    }
                } else {
                    form[id] = valor;
                }
            }
        }
    });
    $.each(form, function (i, e) {
        if (e !== null) {//caso o combo box esteja vazio
            if (e.indexOf('|pkj|') !== -1) {
                delete form[i];
                var tmp = e.split('|pkj|');
                if (tmp[0] === '|POG|' && tmp.length === 2) {
                    tmp.splice(0, 1);
                }
                form[i] = tmp;
            }
        }
    });
    //form = eval("(" + JSON.stringify(form) + ")");
    return form;
}
function lock(elemento) {
    if (jQuery(elemento).is(':disabled')) {
        jQuery(elemento).removeAttr('disabled');
    } else {
        jQuery(elemento).attr('disabled', 'true');
    }
}
$(function () {
    bindRefresh();
    setInterval(function () {
        bindRefresh();
    }, 100);
});
function fileData(input, ok) {
    var f = $(input).prop("files")[0];
    var leitor = new FileReader();
    var gatinhoFeliz = $(input);
    leitor.onload = function (e) {
        ok($(input).val() + "|filepkj|" + leitor.result, gatinhoFeliz);
    };
    leitor.readAsDataURL(f);
}