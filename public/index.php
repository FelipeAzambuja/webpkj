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
            exit();
        }
        alert($form['nome']);
    }
    ?>
</form>
<?php