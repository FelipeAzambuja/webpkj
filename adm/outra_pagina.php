<?php

function init() {
    page("adm/outra_pagina.php:outra_page_feliz", "#pkjpage")->go();
}

function teste($form) {
    $data = [
        ["nome" => "n1"],
        ["nome" => "n2"],
    ];

    ob_start();
    ?>
    <table class="datatables">
        <thead>
            <tr>
                <th>Nome</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $d): ?>
                <tr>
                    <td><?= $d["nome"] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    $html = ob_get_clean();
    page("adm/outra_pagina.php:outra_page_feliz", "#pkjpage")->html("#data", $html);
}
?>

<div page="outra_page_feliz" init="teste">
    <div id="data"></div>


</div>
<div page="root" init="init">
    O {{nome}} veio de outra pagina entre agora na outra page feliz <br>
    {{range}}
    <a href="#" onclick="page.go('adm/outra_pagina.php:outra_page_feliz', '#pkjpage', {range: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]})"> Outra Pagina feliz</a>
</div>
