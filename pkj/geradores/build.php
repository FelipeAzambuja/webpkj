<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
        <?php
        import("jquery");
        import("bind");
        import("bootstrap");
        ?>
    </head>
    <body>
        <div class="container">
            <form>
                <?php
                text("arquivo", "placeholder='glob'", 10);
                button("Adicionar", "click='adicionar()' load-text='Carregando' lock", 2);

                function adicionar($param) {
                    $arquivo = glob("../../" . $param["arquivo"]);
                    if (count($arquivo) < 1) {
                        return;
                    }
                    ob_start();
                    foreach ($arquivo as $a) {
                        $a = realpath($a);
                        if (is_file($a)) {
                            row();
                            text("glob[]", "value='$a'", 10);
                            button("Remover", "onclick='$(this).parent().parent().remove()'", 2);
                            row();
                        }
                    }
                    $html = ob_get_contents();
                    ob_end_clean();
                    append("#lista", $html);
                    setValue("#arquivo", "");
                    focus("#arquivo");
                }
                ?>
                <div id="lista" class="row">

                </div>
                <?php
                text("saida", "placeholder='Pasta de saida'", 9);
                button("Gerar", "click='gerar()' load-text='Gerando' lock", 3);

                function gerar($param) {
                    if ($param["saida"] == "") {
                        alert("Especifique uma pasta de saida");
                        return;
                    }
                    $out = "../../" . $param["saida"];
                    foreach ($param["glob"] as $g) {
                        if (endswith($g, ".php")) {
                            ob_start();
                            include $g;
                            $tmp = ob_get_contents();
                            ob_end_clean();
                            $tmp = str_replace(conf::$pkjHome . "/client", "pkj", $tmp);
                            $tmp = str_replace(conf::$pkjHome . "/server", $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . conf::$pkjHome . "/server", $tmp);
                            $tmp = str_replace('"/pkj/server/pkjall.php";', '"' . basename($g) . '";', $tmp);
                            $html_name = str_replace(".php", ".html", basename($g));
                            file_put_contents($out . "/" . $html_name, $tmp);
                        }
                    }
                    copyr("../client", $out . "/pkj");
                    $pack = <<<OUT
                            {
                                "name": "nw-demo",
                                "version": "0.0.1",
                                "main": "index.html"
                            }
OUT;
                    file_put_contents($out."/package.json", $pack);
                }
                ?>
            </form>
        </div>
    </body>
</html>