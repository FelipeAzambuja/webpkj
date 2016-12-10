<?php
include './pkj/server/all.php';

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
        import("icheck");
        import("bootstrap");
        import("bind");
        import("bpopup");
        import("mask");
        import("chosen");
        import("datatables");
        ?>
    </head>
    <body>
        <form class="container">
                <?php
                row();
                text("chave", "placeholder='Chave' modifier='underbar' float", 6);
                text("valor", "placeholder='Valor' modifier='underbar' float", 6);
                row();

                row();
                check("liquidos[]", "Agua","liquidos[]","data-id='23'", 6);
                check("liquidos[]", "Vinho","liquidos[]","data-id='43432'", 6);
                row();

                row();
                radio("idade", "0-20", "idade","data-id='1'");
                radio("idade", "20-30", "idade","data-id='2'");
                radio("idade", "30-40", "idade","data-id='3'");
                radio("idade", "40+", "idade","data-id='4'");
                row();


                row();
                button("Ler", "click='ler()' color='danger'", 6);
                button("Gravar", "click='gravar()'", 6);
                row();
                ?>
        </form>

    </body>
</html>
