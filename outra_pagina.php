<?php
function init() {
    page("outra_pagina.php:root","#pkjpage")->update(["nome"=>"Felipe Nunes Azambuja","range"=>  range(1, 100)]);
}
?>
<div page="outra_page_feliz">
    {{#each range}}
    Bem vindo a outra pagina {{this}}<br>
    {{/each}}
</div>
<div page="root">
O {{nome}} veio de outra pagina entre agora na outra page feliz <br>
{{range}}
<a href="#" onclick="page.go('outra_pagina.php:outra_page_feliz','#pkjpage',{range:[ {{range}} ]})"> Outra Pagina feliz</a>
</div>
