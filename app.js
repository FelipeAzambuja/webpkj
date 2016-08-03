var loads = ["bind","onsen", "remote", "popup", "mask"];
var conexao = null;
PKJ.require(loads, function () {
    $("body").show();
//    ons.ready(function () {
//        bindRefresh();
//    });
    PKJ.refresh();
});

window.fn = {};

window.fn.open = function () {
    var menu = document.getElementById('menu');
    menu.open();
};

window.fn.load = function (page) {
    var content = document.getElementById('content');
    var menu = document.getElementById('menu');
    content.load(page)
            .then(menu.close.bind(menu));
};
var index = {};
index.teste = function(value){
    console.log(value);
}

