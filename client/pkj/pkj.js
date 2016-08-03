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
PKJ.loadedLibrarys = [];
PKJ.refresh = function () {
    PKJ.loadedLibrarys.forEach(function (e) {
        switch (e) {
            case "bootstrap":
                document.querySelectorAll("input,select,textarea").forEach(function (e) {
                    if (!in_array("form-control", e.classList)) {
                        e.classList.add("form-control");
                    }
                });
                break;
            case "bind":
                $("*").removeAttr("bind");
                bindRefresh();
                break;
        }
    });
};
PKJ.getRootPath = function () {
    var local = location.href;
    var tmp = document.getElementsByTagName("script");
    var currentScript = null;
    for (var i = 0; i < tmp.length; i++) {
        if(tmp[i].src.indexOf("pkj.js") >= 0){
          currentScript = tmp[i].src;
          break;
        }
    }
    var parser = document.createElement('a');
    parser.href = currentScript;
    local = parser.pathname.replace("pkj/pkj.js","");
    if (local.substring(local.lenght - 1) === "/") {
        return local;
    }
    local = local.replace(/\/[a-zA-Z]{0,}\.{1}[a-zA-Z]{0,5}/gi, "/");
    return local;
};
PKJ.injectJavaScript = function (src, ok) {
    src = PKJ.getRootPath() + src;
    var js = document.createElement("script");
    js.setAttribute("src", src);
    js.setAttribute("type", "text/javascript");
    js.onload = ok;
    document.head.appendChild(js);
    return true;
};
PKJ.injectCss = function (src, ok) {
    src = PKJ.getRootPath() + src;
    var css = document.createElement("link");
    css.setAttribute("href", src);
    css.setAttribute("rel", "stylesheet");
    css.onload = ok;
    document.head.appendChild(css);
    return true;
};
PKJ.require = function (librarys, onload) {
    var e = "";
    PKJ.injectCss("pkj/pkj.css");
    for (var i = 0; i < librarys.length; i++) {
        e = librarys[i];
        if (i === (librarys.length - 1)) {
            PKJ.loadLibrary(e, onload);
        } else {
            PKJ.loadLibrary(e);
        }
    }
};
PKJ.loadLibrary = function (name, ok) {
    var load = true;
    PKJ.loadedLibrarys.forEach(function (e) {
        if (e === name) {
            console.error("Library " + name + " is loaded");
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
