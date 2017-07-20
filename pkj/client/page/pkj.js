var page = {};
page.enableCache = true;

page.cache = [];
page.inits = [];
page.history = [];
page._getHTML = function (name, done) {
    var e = $("div[page='" + name + "']");
    if (e.size() === 0) {
        var slit = name.split(/:/);
        var _page = slit[0];
        var element = slit[1];
        if (page._inCache(element,_page)) {
            done(page.cache[element+""+_page]);
        } else {
            $.get(_page, function (html) {
                html = page._parser(html, element,_page);
                done(html);
            });
        }
    } else {
        var element = name;
        var html = e.html();
        page.inits[element] = e.attr("init");
        done(html);
    }
};
page._inCache = function (element,_page) {
    return typeof page.cache[element+""+_page] !== "undefined";
};
page._parser = function (html, element,_page) {
    if (page.enableCache) {
        if (page._inCache(element,_page)) {
            return page.cache[element+""+_page];
        }
    }
    var retorno = $.parseHTML(html,document,true);
    retorno = retorno[retorno.map(function (e) {
        return $(e).attr("page");
    }).indexOf(element)];
    if (typeof retorno.attributes.init !== "undefined") {
        page.inits[element] = retorno.attributes.init.nodeValue;
    } else {
        page.inits[element] = undefined;
    }
    retorno = retorno.innerHTML;
    if (page.enableCache) {
        page.cache[element+""+_page] = retorno;
    }
    return retorno;
};
page._get = function (name, done) {
    return page._getHTML(name, done);
};
page.render = function (name, outputElement, data) {
    page._get(name, function (html) {
        var _name = "";
        var s = name.split(/:/);
        if (s.length > 0) {
            _name = s[1];
        } else {
            _name = name;
        }
        if (typeof page.inits[_name] !== "undefined") {
            var _page = "";
            if (s.length > 0) {
                _page = s[0];
                _name = s[1];
            } else {
                _name = name;
            }
            var fun = page.inits[_name];
            if (typeof data === "undefined" || data.length === 0) {
                bindCall(_page, fun);
            } else {
                bindCall(_page, fun, data);
            }
            $(outputElement).attr("load-page", name);
        } else {
            $(outputElement).attr("load-page", name);
        }

        $(outputElement).html('');
        $(outputElement).html(html);

    });
};

page.go = function (name, outputElement, data) {
    page.render(name, outputElement, data);
    page.history.push({
        name: name,
        outputElement: outputElement,
        data: data
    });
};
page.update = function (name, outputElement, data) {
    page.render(name, outputElement, data);
};
page.back = function (data) {
    if (page.history.length > 0) {
        var _page = page.history[page.history - 1];
        //verificar
        if (typeof _page.data === "undefined") {
            _page.data = data;
        }
        page.update(_page.name, _page.outputElement, _page.data);
    }
};