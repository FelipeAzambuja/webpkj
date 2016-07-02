var loads = ["onsen", "remote", "bind", "popup", "mask"];
var conexao = null;
PKJ.require(loads, function () {
  $("body").show();
  conexao = new RemoteConnection("http://localhost/webpkj/server/pkj/driver.php","felipe","123");
  conexao.execute("delete from usuarios");
  conexao.query("select * from usuarios",function(data){
    console.log(data);
  });
  conexao.execute("insert into usuarios (nome,telefone,idade) values(?,?,?)",["Felipe","",26]);
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
function teste(value) {
    console.log(value);
}
