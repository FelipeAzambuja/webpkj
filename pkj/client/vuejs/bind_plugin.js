var Bind = {};
Bind.install = function (Vue) {
    Vue.prototype.$bind = {};
    Vue.bind.router = "";
    Vue.prototype.$bind.call = function (pagina, funcao, data, done) {
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
        $.ajaxSetup({
            xhrFields: {
                withCredentials: true
            },
            cache: false
        });
        data.HOST = window.location.protocol + "//" + window.location.hostname;
        data.GET = $_GET;
        $.post(Vue.bind.router, data, function (resp) {
            if (len(trim(resp)) > 0) {
                try {
                    eval(resp);
                } catch (e) {
                    console.log(resp);
                    console.log(e);
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
        });
    }
}