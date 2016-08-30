
window.fn = {};

window.fn.open = function () {
    var menu = document.getElementById('menu');
    menu.open();
};

window.fn.load = function (page) {
    var content = document.getElementById('content');
    var menu = document.getElementById('menu');
    content.load(page)
            .then(function () {
                menu.close.bind(menu);
                bindRefresh()
            });
};
var index = {};
index.teste = function (value) {
    console.log(value);
}

