function lcase(texto) {
    if (typeof (texto) === "undefined") {
        return "";
    }
    return texto.toLowerCase();
}
function ucase(texto) {
    if (typeof (texto) === "undefined") {
        return "";
    }
    return  texto.toUpperCase()
}
function trim(texto) {
    if ($.isArray(texto)) {
        texto = texto[0];
    }
    if (typeof (texto) === "undefined") {
        return "";
    }
    return texto.replace(/^\s+|\s+$/g, "");
}

function replace(texto, procura, valor) {
    if (typeof (texto) === "undefined") {
        return "";
    }
    while (texto.indexOf(procura) !== -1) {
        texto = texto.replace(procura, valor);
    }
    return texto;
}
function indexof(texto, valor) {
    return find(texto, valor);
}
function indexOf(texto, valor) {
    return find(texto, valor);
}
function find(texto, valor) {
    return texto.indexOf(valor) + 1;
}
function len(texto) {
    if (typeof (texto) === "undefined") {
        return 0;
    }
    return texto.length;
}
function substring(texto, inicio, quantidade) {
    return texto.substr(inicio, quantidade);
}
function part(texto, separador) {
    return texto.split(separador);
}
function in_array(texto, array) {
    for (i = 0; i < array.length; i++) {
        if (texto === array[i]) {
            return true;
        }
        return false;
    }
}
PKJ = {};
PKJ.refresh = function (load) {
    ["bootstrap", "bind", "icheck"].forEach(function (e) {
        switch (e) {
            case "icheck":
                try {
                    $("input").iCheck({checkboxClass: 'icheckbox_square-aero', radioClass: 'iradio_square-aero', increaseArea: '20%'})
                } catch (e) {

                } finally {

                }

                break;
            case "bootstrap":
                try {
                    var hasOnsen = ($("script[src*='onsenui.min.js']").size() > 0);
                    if (!hasOnsen) {
                        document.querySelectorAll("input[type='text'],input[type='password'],select,textarea").forEach(function (e) {
                            if (!in_array("form-control", e.classList)) {
                                e.classList.add("form-control");
                            }
                        });
                    }
                } catch (e) {

                } finally {

                }
                break;
            case "bind":
                if (typeof (bindRefresh) !== "undefined") {
                    $("*").removeAttr("bind");
                    bindRefresh();
                } else {
                    setTimeout(function () {
                        PKJ.refresh();
                    }, 1000);
                }
                break;
        }
        if (typeof (load) !== "undefined") {
            load();
        }
    });
};

PKJ.loadLibrary = function (name, ok) {
    var load = true;
    PKJ.loadedLibrarys.forEach(function (e) {
        if (e === name) {
            //console.error("Library " + name + " is loaded");
            load = false;
        }
    });
    if (!load) {
        return;
    }
    if (ok === undefined) {
        ok = function () {}
    }
    PKJ.loadedLibrarys.push(name);
    switch (name) {
        case "remote":
            PKJ.injectJavaScript("pkj/connection/remote.js", ok);
            break;
        case "onsenui":
        case "onsen":
            PKJ.injectCss("pkj/onsen/css/onsenui.css");
            PKJ.injectCss("pkj/onsen/css/onsen-css-components.css");
            PKJ.injectJavaScript("pkj/onsen/js/onsenui.js", function () {
                PKJ.refresh();
                ok();
            });
            break;
        case "bootstrap":
            PKJ.injectCss("pkj/bootstrap/css/bootstrap-theme.min.css");
            PKJ.injectCss("pkj/bootstrap/css/bootstrap.min.css");
            PKJ.injectCss("pkj/bootstrap/css/pkj.css");
            PKJ.injectJavaScript("pkj/bootstrap/js/bootstrap.min.js", function () {
                PKJ.refresh();
                ok();
            });
            break;
        case "mobile":
            PKJ.injectCss("pkj/mobile/jquery.mobile-1.4.5.min.css");
            PKJ.injectCss("pkj/mobile/jquery.mobile.theme-1.4.5.min.css");
            PKJ.injectCss("pkj/mobile/pkj.css");
            PKJ.injectJavaScript("pkj/mobile/jquery.mobile-1.4.5.min.js", ok);
            break;
        case "ui":
            PKJ.injectCss("pkj/ui/css.css");
            PKJ.injectJavaScript("pkj/ui/js.js", ok);
        case "sqlite":
            PKJ.injectJavaScript("pkj/connection/sqlite.js", ok);
            break;
        case "bind":
            PKJ.injectJavaScript("pkj/bind/bind.js", ok);
            break;
        case "bpopup":
        case "popup":
            PKJ.injectCss("pkj/bpopup/popup.css");
            PKJ.injectJavaScript("pkj/bpopup/jquery.bpopup.min.js", function () {
                PKJ.injectJavaScript("pkj/bpopup/util.js", ok);
            });
            break;
        case "mask":
            PKJ.injectJavaScript("pkj/mask/mask.js", function () {
                PKJ.injectJavaScript("pkj/mask/maskMoney.js", ok);
            });
            break;
        case "swipe":
            PKJ.injectJavaScript("pkj/swipe/jquery.touchSwipe.min.js", ok);
            break;
        case "shorcut":
            PKJ.injectJavaScript("pkj/shortcut/shortcut.js", ok);
            break;
        default:
            console.error("Library not found");
    }
};

function tagUpdate() {


    //datatables
    if (typeof ($.fn.dataTableExt) !== "undefined") {
        $(".datatables").each(function (i, e) {
//            $(function () {
                var responsive = $(e).hasClass("datatables-responsive");
                if (!$.fn.dataTable.isDataTable(e)) {
                    var tabela = $(e).DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        responsive: responsive,
                        select: true,
                        keys: true,
                        "language": {
                            "sEmptyTable": "Nenhum registro encontrado",
                            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                            "sInfoPostFix": "",
                            "sInfoThousands": ".",
                            "sLengthMenu": "_MENU_ resultados por página",
                            "sLoadingRecords": "Carregando...",
                            "sProcessing": "Processando...",
                            "sZeroRecords": "Nenhum registro encontrado",
                            "sSearch": "Pesquisar",
                            "oPaginate": {
                                "sNext": "Próximo",
                                "sPrevious": "Anterior",
                                "sFirst": "Primeiro",
                                "sLast": "Último"
                            },
                            "oAria": {
                                "sSortAscending": ": Ordenar colunas de forma ascendente",
                                "sSortDescending": ": Ordenar colunas de forma descendente"
                            }
                        }
                    });
                    tabela.on('responsive-display', function (e, datatable, row, showHide, update) {
//                        console.log('Details for row ' + row.index() + ' ' + (showHide ? 'shown' : 'hidden'));
                        tagUpdate();
                    });
                    tabela.on('draw.dt', function () {
                        tagUpdate();
                    });
                }
//            });
        });
    }

    $("select").each(function (i, e) {
        if ($(e).attr("value") != undefined) {
            $(e).val($(e).attr("value"));
        }
    });
    $("input[type='checkbox']").each(function (i, e) {
        if ($(e).attr("value") != undefined) {
            if ($(e).attr("value") == "true") {
                $(e).attr("checked", "true");
            } else {
                $(e).removeAttr("checked");
            }
        }

    });
    var contador = 0;
    var hasOnsen = ($("script[src*='onsenui.min.js']").size() > 0);
    $('input').each(function (i, e) {
        if (ucase($(e).attr("type")) === "BUTTON") {
            $(e).addClass("btn");
            var color = lcase($(e).attr("color"));
            if (color === "") {
                color = "primary";
            }
            $(e).addClass("btn-" + color);
        }
        if (!hasOnsen) {
            if (!$(e).hasClass("form-control")) {
                $(e).addClass("form-control");
            }
        }
        if ($(e).attr("data-mask") != undefined) {
            $(e).mask($(e).data("mask")).removeAttr("data-mask");
        }
        if ($(e).attr("data-calendar") != undefined) {
            try {
                $(e).datepicker({showButtonPanel: true});
            } catch (e) {
                console.log(e.message);
            }
            $(e).mask('99/99/9999').removeAttr("data-calendar");
        }
        if ($(e).attr("data-number") != undefined) {
            $(e).maskMoney({'precision': '0'}).removeAttr("data-number");
        }
        if ($(e).attr("data-money") != undefined) {
            $(e).maskMoney({'decimal': '.'}).removeAttr("data-money");
        }
        if ($(e).attr("data-autocomplete") != undefined) {
            $(e).autocomplete({source: $(e).data("autocomplete")}).removeAttr("data-autocomplete");
        }

        if ($(e).attr("data-upload") != undefined) {
            var gato1 = e;
            var id = $(e).attr('id');
            $(e).append('<input type="hidden" contador="' + contador + '" id="' + id + '" value="null" />');
            $(e).attr('contador', contador);
            $(e).removeAttr('id');
            $(e).click(function () {
                id = (id.replace("[", "\\[")).replace("]", "\\]");
                $("#" + id + "[contador='" + contador + "']").val("null");
            });
            $(e).change(function () {
                fileData($(gato1), function (d, gato2) {
                    var gato3 = $(gato2).attr('contador');
                    id = (id.replace("[", "\\[")).replace("]", "\\]");
                    $('#' + id + '[contador="' + gato3 + '"]').val(d);
                });
            }).removeAttr("data-upload");
        }
        contador++;
    });
}
$(function () {
    if(typeof page.go == "undefined"){
        tagUpdate();
    }
});
/** heredoc(function({/*   ... *\/})
 * https://stackoverflow.com/questions/4376431/javascript-heredoc/21789375#21789375
 * https://stackoverflow.com/users/1008429/nate-ferrero
 * Não usar
 * @param {type} f
 * @returns {unresolved}
 */
function _heredoc(f) {
    var s = f.toString();
    s = s.substring(14);// ou 13 conforme o espaço
    s = s.substring(0,s.length - 4);
    return s.trim();
//    return f.toString().match(/\/\*\s*([\s\S]*?)\s*\*\//m)[1].replace(/(\/\*[\s\S]*?\*) \//g, '$1/');
}
;

var $_GET = {};
document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
    function decode(s) {
        return decodeURIComponent(s.split("+").join(" "));
    }
    $_GET[decode(arguments[1])] = decode(arguments[2]);
});
