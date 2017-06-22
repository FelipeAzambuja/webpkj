var page = {};
page.historico = [];
//Verificar uso de ram
page.data = [];
page.html = [];
page._getE = function (name) {
    return $(name);
}
page._get = function (name) {
    if (name.indexOf(".") > 0) {
        var page_name = null;
        if (name.indexOf(":") > 0) {
            page_name = name.split(/:/g)[1];
            name = name.split(/:/g)[0];
        }
        var retorno = "";
        $.ajax({
            url: name,
            dataType:"html",
            success: function (data, textStatus, jqXHR) {
                retorno = data;
            },
            async: false//retirar o async para melhorar o desempenho
        });
        if (page_name != null) {
            //jquery tem dessas
            var retorno = $.parseHTML(retorno);
            retorno = retorno[retorno.map(function(e){ return $(e).attr("page"); }).indexOf(page_name)];
        }
        return retorno.innerHTML;
    } else {
        if (typeof page.html[name] != "undefined") {
            return page.html[name];
        } else {
            page.html[name] = $('div[page="' + name + '"]').html();
            return page.html[name];
        }
    }
}

page._partials = function () {
    var p = {};
    $("div[template]").toArray().forEach(function (e) {
        p[$(e).attr("template")] = $(e).html();
    });
    return p;
}

page.hide = function (name) {
    page._getE(name).hide();
}

page.back = function (data) {
    if (typeof data == "undefined") {
        data = [];
    }
    if (page.historico.length > 1) {
        atual = page.historico[page.historico.length - 1];
        anterior = page.historico[page.historico.length - 2];
        page.historico = page.historico.slice(0, -1);
        page._getE(atual).hide();
        if (data.length > 0) {
            var template = page._get(anterior);
            var rendered = Mustache.render(template, data, page._partials());
            page._getE(anterior).html(rendered).show();
        } else {
            page._getE(anterior).show();
        }
        page.data[anterior] = data;
    }
}
page.show = function (name, outputElement, data) {
    if (typeof data == "undefined") {
        data = [];
    }
    var template = page._get(name);
    var rendered = Mustache.render(template, data, page._partials());
    page._getE(outputElement).html(rendered).show();
    page.data[name] = data;
}

page.go = function (name, outputElement, data) {
	name_orig = name;
    if (typeof data == "undefined") {
        data = [];
    }
    var template = page._get(name);
    var partials = page._partials();
    var rendered = Mustache.render(template, data, partials);
    page._getE(outputElement).html(rendered).show();
    if (typeof bindCall != "undefined" && name.indexOf(".") > 0) {
        if (name.indexOf(":") > 0) {
            name = name.split(/:/g)[0];
        }
		bindCall(name, "init");
		
    }
    if (page.historico.length > 0) {
        var ultimo = page.historico[page.historico.length - 1];
        page._getE(ultimo).hide();
    }
    page.historico.push(name_orig);
    page.data[name_orig] = data;
};

page.update = function (name, outputElement, data) {
    if (typeof data == "undefined") {
        data = [];
    }
    console.log("Você está atualizando a pagina "+name+" e a atual é "+page.historico[page.historico.length-1]);
    if(name != page.historico[page.historico.length-1]){
		return;
	}
    var template = page._get(name);
    var rendered = Mustache.render(template, data, page._partials());
    page._getE(outputElement).html(rendered);
    page.data[name] = data;
}
