var page = {};
page.historico = [];
page._get = function (name) {
    return $('div[page="' + name + '"]');
}

page._partials = function () {
    var p = {};
    $("div[template]").toArray().forEach(function (e) {
        p[$(e).attr("template")] = $(e).html();
    });
    return p;
}

page.hide = function (name) {
    page._get(name).hide();
}

page.back = function (data) {
    if (typeof data == "undefined") {
        data = [];
    }
    if (page.historico.length > 1) {
        atual = page.historico[page.historico.length - 1];
        anterior = page.historico[page.historico.length - 2];
        page.historico = page.historico.slice(0, -1);
        page._get(atual).hide();
        if (data.length > 0) {
            var template = page._get(anterior).html();
            var rendered = Mustache.render(template, data, page._partials());
            page._get(anterior).html(rendered).show();
        } else {
            page._get(anterior).show();
        }
    }
}
page.show = function (name, data) {
    if (typeof data == "undefined") {
        data = [];
    }
    var template = page._get(name).html();
    var rendered = Mustache.render(template, data, page._partials());
    page._get(name).html(rendered).show();
}
page.go = function (name, data) {
    if (typeof data == "undefined") {
        data = [];
    }
    var template = page._get(name).html();
    var partials = page._partials();
    var rendered = Mustache.render(template, data, partials);
    page._get(name).html(rendered).show();
    if (page.historico.length > 0) {
        var ultimo = page.historico[page.historico.length - 1];
        page._get(ultimo).hide();
    }
    page.historico.push(name);
}