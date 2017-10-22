<?php $template = "templates/template.php"; ?>
<form>
    <?php
    label('Nome');
    text('nome');
    button('Adicionar','click="adicionar()" lock');
    function adicionar($form){
        alert($form['nome']);
    }
    ?>
</form>