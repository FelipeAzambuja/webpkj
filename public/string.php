<?php
$template = 'templates/template.php';
function mostrarValor($form) {
    alert(gettype($form['valor']));
    setValue('name=\'valor\'', $form['valor']+10);
}
?>
<form>
    <?php
    label_money('Valor', 'valor');
    label_button('Enviar','click="mostrarValor()" lock');
    ?>
</form>
