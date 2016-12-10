<?php
include '../pkj/server/all.php';

function ler($form) {
    alert(session_get($form["chave"]));
}

function gravar($form) {
    session_set($form["chave"], $form["valor"]);
    alert("Valor gravado $form[valor] na chave $form[chave]");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php
        import("jquery");
        import("bind");
        import("datatables");
        import("onsen");
        import("bootstrap");
        ?>
        <script type='text/javascript'>
            ons.ready(function () {
                ons.disableAnimations();
                ons.forcePlatformStyling('android');
                ons.platform.select('android');
            });
        </script>
    </head>
    <body>
    <ons-splitter>
        <ons-splitter-side id="menu" side="left" collapse swipeable>
            <ons-page>
                <ons-list>
                    <ons-list-item tappable>Home</ons-list-item>
                    <ons-list-item tappable>Home</ons-list-item>
                    <ons-list-item tappable>Home</ons-list-item>
                    <ons-list-item tappable>Home</ons-list-item>
                </ons-list>
            </ons-page>
        </ons-splitter-side>
        <ons-splitter-content id="conteudo" page="home.html"></ons-splitter-content>
    </ons-splitter>


    <ons-template id="home.html"> 
        <ons-page>
            <ons-toolbar>
                <div class="left">
                    <ons-toolbar-button>
                        <ons-icon icon="md-menu"></ons-icon>
                    </ons-toolbar-button>
                </div>
                <div class="center">Aplicativo</div>
            </ons-toolbar>

            <form class="container" style="margin-top:20px">

                <?php
                row();
                text("chave", "placeholder='Chave' modifier='underbar' float", 6);
                text("valor", "placeholder='Valor' modifier='underbar' float", 6);
                row();

                row();
                check("agua", "Agua", 6);
                check("vinho", "Vinho", 6);
                row();

                row();
                radio("idade", "0-20", "idade");
                radio("idade", "20-30", "idade");
                radio("idade", "30-40", "idade");
                radio("idade", "40+", "idade");
                row();


                row();
                button("Ler", "click='ler()' color='danger'", 6);
                button("Gravar", "click='gravar()'", 6);
                row();
                ?>
            </form>
        </ons-page>
    </ons-template>


</body>
</html>
