<?php

function init() {
//  c ( "iniciando" );
}

function click($form) {
    alert("você é muito feliz");
}
?>
<!doctype html>
<html >
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
        <?php
        import("jquery");
        import("bootstrap");
        import("bind");
        ?>
    </head>
    <body>
        <div class="container" id="home">
            <form>
                <?php
                button("Teste", "click='click()' lock", 12);
                ?>
            </form>
        </div>
    </body>
</html>