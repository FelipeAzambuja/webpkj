<?php
//db_usuario()->create();

function init() {
    $data = array();
    $data["projeto"] = "Webpkj";
    page("home")->go($data);
}
?>
<!doctype html>
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
        <div template="header">
            <h1>Cadastro no {{projeto}}</h1>
            <hr style="border: 1px solid black" />
        </form>
    </div>
    <div template="footer"></div>

    <div class="container-fluid"  page="home">
        
        <div class="well" style="margin-top: 15px">
            {{>header}}
            
            <form >
                <?php
                row();
                text("nome", "placeholder='Seu Nome'", 12);
                password("senha", "placeholder='Sua senha'", 12);

                button("Entrar", "color='default' click='entrar()' lock", 6);

                function entrar($form,$page) {
                    if (db_usuario()->login($form["nome"], $form["senha"])) {
                        page("tela_inicial")->go($form);
                    } else {
                        $page["home"]["erros"][] = "Errou com <b>$form[nome]</b>";
                        page("home")->update($page["home"]);
//                        alert("Login e Senha invalidos");
                    }
                }

                button("Cadastrar", "color='primary' load-text='Carregando' click='cadastrar()' lock", 6);

                function cadastrar($form) {
                    $form["projeto"] = "Webpkj";
                    unset($form["nome"]);
                    page("cadastrando")->go($form);   
                }

                row()
                ?>
            </form>    
            {{#erros}}
                {{.}}<br>
            {{/erros}}
        </div>

    </div>

    <div class="container well well-lg "  page="cadastrando">

        <form class="row">
            <h1>Cadastro {{projeto}}</h1>
            <?php
            text("nome", "value='{{nome}}' placeholder='Seu Nome'", 12);
            text("senha", "value='{{senha}}' placeholder='Sua senha'", 12);
            text("confirme", "placeholder='Sua senha'", 12);

            button("Confirmar", "color='default' click='confirmar()' lock", 6);

            function confirmar($form) {
                if ($form["senha"] != $form["confirme"]) {
                    alert(" Confirme a senha ");
                    exit();
                }
                
                $id = db_usuario()->
                        setNome($form["nome"])->
                        setSenha($form["senha"])->
                        save();
                $form["id"] = $id;
                page("finalizado")->go($form);
            }

            button("Cancelar", "color='danger' click='cancelar()' lock", 6);

            function cancelar($form) {
                page()->back();
            }
            ?>
        </form>
    </div>

    <div class="container" page="finalizado">
        Seu Cadastro foi finalizado {{nome}} {{id}}
        <a href="#" onclick="page.go('home')">Inicio</a>
    </div>

    <div class="container" style="display: none" page="tela_inicial">
        Bem vindo {{nome}} <br> <a href="#" onclick="page.go('home')">Sair</a>
    </div>

</body>
</html>