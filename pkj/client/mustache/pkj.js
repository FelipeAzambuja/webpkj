var page = {};
page.historico = [];
//Verificar uso de ram
page.data = []; 
page.html = [];
page._getE = function (name) {
    return $('div[page="' + name + '"]');
}
page._get = function (name) {
    if(typeof page.html[name] != "undefined"){
        return page.html[name];
    }else{
        page.html[name] = $('div[page="' + name + '"]').html();
        return page.html[name];
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
page.show = function (name, data) {
    if (typeof data == "undefined") {
        data = [];
    }
    var template = page._get(name);
    var rendered = Mustache.render(template, data, page._partials());
    page._getE(name).html(rendered).show();
    page.data[name] = data;
}
page.go = function (name, data) {
    if (typeof data == "undefined") {
        data = [];
    }
    var template = page._get(name);
    var partials = page._partials();
    var rendered = Mustache.render(template, data, partials);
    page._getE(name).html(rendered).show();
    if (page.historico.length > 0) {
        var ultimo = page.historico[page.historico.length - 1];
        page._getE(ultimo).hide();
    }
    page.historico.push(name);
    page.data[name] = data;
}
page.update = function (name,data) {
    return page.show(name,data);
}