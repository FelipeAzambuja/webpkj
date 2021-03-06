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
    if (texto === undefined) {
        debugger;
    }
    return texto.split(separador);
}
function in_array(texto, array) {
    for (var i = 0; i < array.length; i++) {
        if (texto === array[i]) {
            return true;
        }
    }
    return false;
}

PKJ = {};
PKJ.refresh = function (load) {
    return false;
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
    return false;
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
function _pkj_postfix(e) {
    if (e.attr('data-frac_digits') !== undefined) {

        var digits = e.attr('data-frac_digits');
        e.val(numeral(e.val()).value().toFixed(digits));

    }
}
function tagUpdate() {

    setTimeout(function () {
        try {
            $('select').chosen();
            $('.chosen-container-single').css('width', '100%');
        } catch (e) {

        } finally {

        }
    }, 1);

    setTimeout(function () {
        $("a[pref],span[pref],button[pref]").off("click").on("click", function (e) {
            e.preventDefault();
            var _page = $(this).attr("pref");
            var data = $(this).attr("pdata");
            var s = _page.split(/,/);
            $('.navbar-toggle').click();
            if (typeof data === "undefined") {
                page.go(s[0], s[1], []);
            } else {
                page.go(s[0], s[1], data);
            }
            tagUpdate();
        });
    }, 1);
    setTimeout(function () {
        $('table.bstable').each(function (i, e) {
            var responsive = $(e).hasClass("bstable-responsive");
            $(e).bootstrapTable({
                'toolbar': '.bstable-toolbar',
//                'showRefresh':"true",
                'toggle': 'table',
                'showColumns': true,
                'showExport': true,
//                'detailView':true,
                'trimOnSearch': true,
                'pagination': true,
                'search': true,
                'url': $(e).attr('ajax'),
                'export': true,
                'locale': 'pt-br',
                'copyBtn': true,
                'mobileResponsive': responsive
            });/*.tableExport({
             type: 'excel',
             mso: {fileFormat: 'xmlss',
             worksheetName: ['Table 1', 'Table 2', 'Table 3']
             }
             });*/
        });
    });
    setTimeout(function () {
        if (typeof ($.fn.dataTableExt) !== "undefined") {
            $(".datatables").each(function (i, e) {
//            $(function () {
                if (!$(e).hasClass("table")) {
                    $(e).addClass('table', 'table-striped', 'table-hover', 'table-bordered');
                }
                var responsive = $(e).hasClass("datatables-responsive");
                if (!$.fn.dataTable.isDataTable(e)) {

                    var tabela = $(e).attr('width', '100%').DataTable({
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                        bStateSave: true,
                        dom: "<'row'<'col-md-1 dt-l-fix'l><'col-sm-12 col-md-5 dt-buttons-main'B><'col-sm-12 col-md-6'f>>" + "<'row'<'col-sm-12't>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dt-buttons-main'p>>",
//                        lengthChange: false,
                        buttons: [
                            {
                                extend: 'copy',
                                text: 'Copiar',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'excel',
                                text: 'Excel',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'print',
                                text: 'Imprimir',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'colvis',
                                text: 'Colunas'
                            }
                        ],
                        responsive: responsive,
                        ajax: $(e).attr("ajax"),
                        deferRender: true,
                        select: false,
                        keys: false,
                        "language": {
                            "sEmptyTable": "Nenhum registro encontrado",
                            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                            "sInfoPostFix": "",
                            "sInfoThousands": ".",
                            "sLengthMenu": "_MENU_ ",
                            "sLoadingRecords": "Carregando...",
                            "sProcessing": "Processando...",
                            "sZeroRecords": "Nenhum registro encontrado",
                            "sSearch": " ",
                            "oPaginate": {
                                "sNext": "Próximo",
                                "sPrevious": "Anterior",
                                "sFirst": "Primeiro",
                                "sLast": "Último"
                            },
                            "oAria": {
                                "sSortAscending": ": Ordenar colunas de forma ascendente",
                                "sSortDescending": ": Ordenar colunas de forma descendente"
                            },
                            "buttons": {
                                "copyTitle": "Copiado com sucesso",
                                "copySuccess": {
                                    _: 'Copiado %d linhas para sua área de transferência',
                                    1: 'Copiado 1 linha para sua área de transferência'
                                }
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
    }, 1);
    //datatables


    $("select").each(function (i, e) {
        if ($(e).val() !== $(e).attr("value")) {
            if ($(e).attr("value") != undefined) {
                $(e).val($(e).attr("value"));
            }
        }
    }).change(function () {
        $(this).attr('value', $(this).val());
    });

    /*
     $("input[type='checkbox']").each(function (i, e) {
     if ($(e).attr("value") != undefined) {
     if ($(e).attr("value") == "true") {
     $(e).attr("checked", "true");
     } else {
     $(e).removeAttr("checked");
     }
     }
     });
     */
    var contador = 0;
    var hasOnsen = ($("script[src*='onsenui.min.js']").size() > 0);
    $('input,select').each(function (i, e) {
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
                if (
                        in_array(ucase($(e).attr("type")), ["TEXT", "PASSWORD", "BUTTON", "FILE", "TEL", "NUMBER", "SEARCH"]) ||
                        $(e).get(0).tagName === "SELECT"

                        ) {
//                    $(e).addClass("form-control");
                }
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
            var decimal_point = $(e).attr('data-decimal_point');
            var thousands_sep = $(e).attr('data-thousands_sep');
            var frac_digits = parseInt($(e).attr('data-frac_digits'));
            if (frac_digits > 100) {
                frac_digits = 2;
            }
            numeral.locales.en.delimiters.thousands = thousands_sep;
            numeral.locales.en.delimiters.decimal = decimal_point;
            var v = $(e).val().replace('.', '').replace(',', '.');
            if (v === '') {
                v = 0;
            }
            v = parseFloat(v).toFixed(frac_digits);
            $(e).on('paste', function (event) {
                event.preventDefault();
                var paste_value = event.originalEvent.clipboardData.getData('Text');
                var frac_digits = parseInt($(event.target).attr('data-frac_digits'));
                if (frac_digits > 100) {
                    frac_digits = 2;
                }
                var v = parseFloat(paste_value);
                v = v.toFixed(frac_digits);
                $(e).val(v);
            });
            $(e).on('copy', function (event) {
                event.preventDefault();
                var v = $(event.target).val();
                var decimal_point = $(event.target).attr('data-decimal_point');
                var thousands_sep = $(event.target).attr('data-thousands_sep');
                var frac_digits = parseInt($(event.target).attr('data-frac_digits'));
                if (frac_digits > 100) {
                    frac_digits = 2;
                }
                numeral.locales.en.delimiters.thousands = thousands_sep;
                numeral.locales.en.delimiters.decimal = decimal_point;
                var v = numeral(v).value();
                v = v.toFixed(frac_digits);
                event.originalEvent.clipboardData.setData('text/plain', v);
            });
            $(e).val(v);
            $(e).priceFormat({
                prefix: '',
                centsLimit: frac_digits,
                centsSeparator: decimal_point,
                thousandsSeparator: thousands_sep,
                leadingZero: true,
                clearPrefix: false,
                clearSufix: false
            }).removeAttr("data-money");

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
//    $('body').hide();
//    if(typeof page.go == "undefined"){
    tagUpdate();
//    }
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
    s = s.substring(0, s.length - 4);
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
