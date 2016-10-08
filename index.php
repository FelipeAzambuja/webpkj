<?php
include './pkj/server/all.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="cordova/cordova.js"></script>
        <script src="cordova/cordova_plugins.js"></script>
        <?php
        resource()->import("jquery");
        resource()->import("bootstrap");
//        resource()->import("chosen");
        resource()->import("bind");
        resource()->import("onsen");
        resource()->import("bpopup");
        resource()->csp();
        ?>
        <!--<script type="text/javascript" src="app.js"></script>-->
    </head>
    <body>
        <form class="container">
            <div class="row" style="padding:5px">
                <div class="col-sm-6"><input type="text" class="form-control" id="chave" placeholder="Chave" /></div>
                <div class="col-sm-6"><input type="text" class="form-control" id="valor" placeholder="Valor"/></div>
            </div>
            <div class="row" style="padding:5px">
                <div class="col-sm-6">
                    <input type="button" class="form-control btn-primary" click="ler()" page="pkj/lab.php" value="Ler" />
                </div>
                <div class="col-sm-6">
                    <input type="button" class="form-control btn-primary" click="gravar()" page="pkj/lab.php" value="Gravar" />
                </div>
            </div>  
        </form>
    </body>
</html>
