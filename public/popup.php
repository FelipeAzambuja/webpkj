<?php
$template = 'templates/template.php';

function mostrar_popup ( $form ) {
    ob_start ();
    ?>
    <form class="p-3">
Teste
    </form>
    <?php
    popup ( ob_get_clean () );
}
?>
<form>
    <?php
    label_button ( 'popup' , 'click="mostrar_popup()" lock' );
    ?>
</form>