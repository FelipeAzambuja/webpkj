var bind_out = "body";
function sisBindInterval(e, tipo) {
    if (e === undefined) {
        return;
    }
    var fun = $(e).attr(tipo);
    if (fun !== undefined) {
        var pagina = $(e).attr("page");
        if (pagina === undefined) {
            pagina = "";
        }
        var sisfunHAppyyyy = formData($(e).parents("form:eq(0),tr:eq(0)"));
        var funName = part(fun, "(")[0];
        var atrStr = part(part(fun, "(")[1], ")")[0];
        var atrs = part(atrStr, ",");
        atrStr = "";
        for (i = 0; i < atrs.length; i++) {
            var v = atrs[i];
            eval("sisfunHAppyyyy.post" + i + ' = "' + v + '";')
        }
        if ($(e).attr("lock") !== undefined) {
            lock(e)
        }
        sisfunHAppyyyy.CMD = funName;
        sisfunHAppyyyy.PAGE = pagina;
        $.post(pagina, sisfunHAppyyyy, function (resp) {
            if (len(trim(resp)) > 0) {
                try {
                    eval(resp);
                } catch (e) {
                    console.log(e);
                }
            }
            if ($(e).attr("lock") !== undefined) {
                lock(e)
            }
        }).fail(function (erro) {
            if ($(e).attr("lock") !== undefined) {
                lock(e)
            }
            console.log(erro);
            http = erro.status;
            msg = erro.responseText;
            alert("ERRO " + http + ":" + msg);
        })
    }
    return true
}
function bindRefresh() {
    setTimeout(function () {
        $.each($("ons-button ,ons-range,ons-input,ons-switch,input,select,a,button,img,textarea"), function (e, t) {
            if ($(t).attr("bind") === undefined) {
                var n = "";
                if ($(t).attr("keyup") !== undefined) {
                    n = "keyup";
                    $(t).attr("on" + n, "sisBindInterval(this, '" + n + "')");
                    $(t).keyup(function () {
                        sisBindInterval(t, n);
                    }).attr("bind", true);
                }
                if ($(t).attr("keydown") !== undefined) {
                    n = "keydown";
                    $(t).attr("on" + n, "sisBindInterval(this, '" + n + "')");
                    $(t).keydown(function () {
                        sisBindInterval(t, n);
                    }).attr("bind", true);
                }
                if ($(t).attr("keypress") !== undefined) {
                    n = "keypress";
                    $(t).attr("on" + n, "sisBindInterval(this, '" + n + "')");
                    $(t).keypress(function () {
                        sisBindInterval(t, n);
                    }).attr("bind", true);
                }
                if ($(t).attr("blur") !== undefined) {
                    n = "blur";
                    $(t).attr("on" + n, "sisBindInterval(this, '" + n + "')");
                    $(t).blur(function () {
                        sisBindInterval(t, n);
                    }).attr("bind", true);
                }
                if ($(t).attr("click") !== undefined) {
                    n = "click";
                    $(t).attr("on" + n, "sisBindInterval(this, '" + n + "')");
                    $(t).click(function () {
                        sisBindInterval(t, n);
                    }).attr("bind", true);
                }
                if ($(t).attr("change") !== undefined) {
                    n = "change";
                    $(t).attr("on" + n, "sisBindInterval(this, '" + n + "')");
                    $(t).change(function () {
                        sisBindInterval(t, n);
                    }).attr("bind", true);
                }
            }
        })
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
        })
    }, 128);
}
$(function () {
    bindRefresh();
    $('form').on('keyup keypress', function (e) {
        var code = e.keyCode || e.which;
        if (code === 13) {
            e.preventDefault();
            return false;
        }
    });
});
function bindCall(pagina, funcao, data) {
    data.CMD = funcao;
    data.PAGE = pagina;
    if (data === undefined) {
        data = {};
        data.post0 = "";
    }
    if (data === {}) {
        data.post0 = "";
    }
    if(data.post0 === undefined){
        data.post0 = "";
    }
    $.post(pagina, data, function (resp) {
        if (len(trim(resp)) > 0) {
            try {
                eval(resp);
            } catch (e) {
                console.log(e);
            }
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
        var qtde = 0;
        if (id !== undefined) {
            qtde = $('#' + (id.replace('[]', '')) + "\\[\\]").size();//aeowwww
        }
        var valor = "";
        if (self.attr("type") === "checkbox" || self.attr("type") === "radio") {
            valor = str(self.is(":checked"));
        } else {
            valor = self.val();
        }
        if (id !== undefined) {
            if (id.indexOf('[]') !== -1) {
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
                form[id] = valor;
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
    form = eval("(" + JSON.stringify(form) + ")");
    return form;
}
function lock(elemento) {
    if (jQuery(elemento).is(':disabled')) {
        jQuery(elemento).removeAttr('disabled');
    } else {
        jQuery(elemento).attr('disabled', 'true');
    }
}

