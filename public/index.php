<?php
$template='templates/template.php';
?>
<form>
    <?php
    label_text('Nome', 'nome');
    button('Mostrar Nome','click="mostrarNome()" ');
    function mostrarNome($form) {
        if(is_empty($form['nome'])){
            focus('#nome');
            notify('O Campo nome é obrigatório', 'warn');
            exit();
        }
        alert($form['nome']);
    }
    ?>
</form>
<?php