<?php
include './pkj/server/all.php';

function teste($form) {
    orm_pessoas()->setNome($form["nome"])->save();
    tabela();
}

function msgbox($form){
//    alert($form["nome"]);
}
function init() {
    bind()->setInterval("msgbox", 1000, ["nome"=>"felipe"]);
    tabela();
}

function tabela() {
    ob_start();
    s(orm_pessoas()->query());
    $html = ob_get_contents();
    ob_end_clean();
    html("dados", $html);
    bindUpdate();
}
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
        resource()->import("chosen");
        resource()->import("bind");
        resource()->import("onsen");
        resource()->import("bpopup");
        resource()->csp();
        ?>
        <script type="text/javascript" src="app.js"></script>
    </head>
    <body>
    <ons-splitter>
        <ons-splitter-side id="menu" side="left" width="220px" collapse swipeable>
            <ons-page>
                <ons-list>
                    <ons-list-item onclick="fn.load('home.html')" tappable>
                        Home
                    </ons-list-item>
                    <ons-list-item onclick="fn.load('settings.html')" tappable>
                        Settings
                    </ons-list-item>
                    <ons-list-item onclick="fn.load('about.html')" tappable>
                        About
                    </ons-list-item>
                </ons-list>
            </ons-page>
        </ons-splitter-side>
        <ons-splitter-content id="content" page="home.html"></ons-splitter-content>
    </ons-splitter>
    <!--    <ons-navigator id="navegador" page="home.html"></ons-navigator>-->
    <ons-template id="home.html">

        <ons-page>
            <ons-toolbar>
                <div class="left">
                    <ons-toolbar-button onclick="fn.open()">
                        <ons-icon icon="md-menu"></ons-icon>
                    </ons-toolbar-button>
                </div>
                <div class="center">
                </div>
            </ons-toolbar>
            <p style="text-align: center; opacity: 0.6; padding-top: 20px;"></p>
            <form style="text-align: center">
                <ons-input input-id="nome" type="text" float placeholder="Nome" modifier="underbar" ></ons-input>
                <ons-button click="teste()" >Enviar</ons-button>
            </form>
            <div id="dados"></div>
        </ons-page>
    </ons-template>

    <ons-template id="settings.html">
        <ons-page>
            <ons-toolbar>
                <div class="left">
                    <ons-toolbar-button onclick="fn.open()">
                        <ons-icon icon="md-menu"></ons-icon>
                    </ons-toolbar-button>
                </div>
                <div class="center">
                    Settings
                </div>
            </ons-toolbar>
        </ons-page>
    </ons-template>

    <ons-template id="about.html">
        <ons-page>
            <ons-toolbar>
                <div class="left">
                    <ons-toolbar-button onclick="fn.open()">
                        <ons-icon icon="md-menu"></ons-icon>
                    </ons-toolbar-button>
                </div>
                <div class="center">
                    About
                </div>
            </ons-toolbar>
        </ons-page>
    </ons-template>

</body>
</html>
