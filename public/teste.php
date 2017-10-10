<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <title></title>
        <?php
        import("jquery");
        import("pkj");
        import("bootstrap");
        import("datatables");
        import("bind");
        import("mask");
        ?>

    </head>
    <body>
        <form class="container-fluid">
            <?php
            label('Nome', 1);
            text('nome', 3);
            label('Telefone', 1);
            mask('telefone', '(99)9.9999-9999', 3);
            label('Skype', 1);
            text('skype', 3);

            label('', 9);
            button("Salvar", "click='salvar()' ");

            function init() {
//                html('#tabela', template("tabela", [
//                    "pessoas" => orm_pessoas()->select("true", "order by id desc limit 100")
//                ]));
            }

            function remover($form) {
                orm_pessoas()->delete([
                    "id" => $form["id"]
                ]);
                html('#tabela', template("tabela", [
                    "pessoas" => orm_pessoas()->select("true", "order by id desc limit 100")
                ]));
            }

            function salvar($form) {
                $pessoa = orm_pessoas();
                $pessoa->fromArray($form);
                $pessoa->insert();
                html('#tabela', template("tabela", [
                    "pessoas" => orm_pessoas()->select("true", "order by id desc limit 100")
                ]));
            }
            ?>
            <div id='tabela'>
                <?php
                echo template("tabela", [
                    "pessoas" => orm_pessoas()->select("true", "order by id desc limit 100")
                ]);
                ?>
            </div>
        </form>

    </body>
</html>