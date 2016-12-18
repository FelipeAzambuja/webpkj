<?php
include './pkj/server/all.php';

//include './components/menu-responsive.php';
function teste($form) {
    alert("Olá $form[nome]");
    alert("Bem vindo $form[nome]");
    ob_start();
    ?>
    Bem vindo<br>
    <?= $form["nome"] ?>
    <?php
    popup(ob_get_clean());
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Web PKJ</title>
        <?php
        import("jquery");
        import("bind");
        import("bootstrap");
        import("bpopup");
        ?>
        <!--<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">-->
        <!--<link rel="stylesheet" href="user.css" />-->
        <script type="text/javascript" src="user.js"></script>
        <meta content="width=device-width,initial-scale=1" name=viewport> 
    </head>
    <body>
        <div class="container-fluid">
            <form>
                <?php
                label("Nome");
                text("nome");
                button("Enviar", "click='teste()'");
                ?>
            </form>
        </div>
    </body>
</html>
