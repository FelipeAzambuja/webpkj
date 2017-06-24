<?php
if (get("debug") != "") {
    sd(conf::$pkjHome{0} != "/");
}

function init() {
    $data = array();
    $data["projeto"] = "Webpkj";
    page("home", "#pkjpage")->go($data);
}
?>
<!DOCTYPE HTML>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Document</title>
        <?php
        import("jquery");
        import("bootstrap");
        import("bind");
        import("mustache");
        ?>
    </head>
    <body>

        <div class="container-fluid" style="margin-top: 15px">

            <div class="row">
                <div class="col-sm-12">
                    <a href="#" onclick="page.go('outra_pagina.php:root', '#pkjpage', {'nome': 'felipe'})"> Outra Pagina</a>
                    <a href="#" onclick="page.go('template', '#pkjpage', {'nome': 'felipe'})"> Nesta pagina</a>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="well" id="pkjpage">
                        sou feliz 2
                    </div>            
                </div>
            </div>

        </div>

        <div page="home">
            Bem vindo ao projeto {{projeto}}
        </div>

        <div page="template">
            O {{nome}} esta nesta pagina
        </div>

    </body>
</html>
