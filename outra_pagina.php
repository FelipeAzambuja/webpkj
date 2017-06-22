<?php
function init() {
    page("outra_pagina.php:root","#pkjpage")->update(["nome"=>"Felipe Nunes Azambuja"]);
}
?>
<div page="outra_page_feliz">
    Bem vindo a outra pagina
</div>
<div page="root">
O {{nome}} veio de outra pagina entre agora na outra page feliz <br><a href="#" onclick="page.go('outra_pagina.php:outra_page_feliz','#pkjpage')"> Outra Pagina feliz</a>
</div>
