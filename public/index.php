<?php
$template = 'templates/template.php';

function main() {
    $usuarios = model_usuarios()->get();
    cd($usuarios);
//    if (!true) {
//        $contatos = new UsuarioContatos();
//        $contatos->load('Usuarios', 'usuarios', 'id', 'pessoinhas');
//        c($contatos->get());
//        c(db()->querys);
//    } else {
//        $usuarios = new Usuarios();
//        $usuarios->nome = 'Jaquelinda';
//        $u = $usuarios->insert();
//        c($u);
//    }
}
?>
<form init="main" >
    <?php
    label_text('Nome', 'nome', 12);
    label_upload('Arquivo', 'arquivo[]','multiple="true"', 12);
    button('Mostrar Nome', 'click="mostrarNome" ', 12);

    function mostrarNome($form) {
        if (is_empty($form['nome'])) {
            focus('#nome');
            notify('O Campo nome é obrigatório');
            exit();
        }
        cd($_FILES['arquivo']);
        $parser = new UploadParser('arquivo');
        if ($parser->is_ok()) {
            c($parser->mime());
            c($parser->name());
            c($parser->size());
            c($parser->ext());
            c($parser->base64());
            
        }
        cd($_FILES['arquivo']);
    }
    ?>
</form>
<?php
