function lcase(texto) {
    return texto.toLowerCase();
}
function ucase(texto) {
    return  texto.toUpperCase()
}
function trim(texto) {
    return texto.replace(/^\s+|\s+$/g, "");
}

function replace(texto, procura, valor) {
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
    PKJ.loadedLibrarys.forEach(function (e) {
        switch (e) {
            case "bootstrap":
                document.querySelectorAll("input,select,textarea").forEach(function (e) {if (!in_array("form-control", e.classList)) {e.classList.add("form-control");}});
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
