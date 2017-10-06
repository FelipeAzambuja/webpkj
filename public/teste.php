<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <?php
        import("jquery");
        import("pkj");
        import("bootstrap");
        import("bind");
        ?>
    </head>
    <body>
        <form>
            <?php
            label("Nome");
            text("nome");
            button("Mostrar", "click='mostrar()' ");

            function init() {
                alert("Bem vindo");
            }

            function mostrar($form) {
                console(db()->select("pessoas", "true"));
//                console(orm_pessoas()->select("true", "limit 10"));
            }
            ?>
        </form>
    </body>
</html>