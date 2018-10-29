<?php
$template = 'templates/template.php';

function form($form) {
    c($form);
}
?>

<form>
    <?php
    text('Texto');
    button('Enviar', 'click="form()" lock');
    ?>
</form>