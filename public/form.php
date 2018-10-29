<?php
$template = 'templates/template.php';

function form($form) {
    c($form);
}
?>

<form>
    <?php
    echo implode(",", array_map(function($v) {
                return is_string($v) ? '\'' . $v . '\'' : (($v === null) ? 'null' : $v);
            }, [1, 0, 'a', null, 'teste',file_get_contents('mulher.jpg')]));
    ?>
</form>