<?php
$template = 'templates/template.php';
?> 

<form class="row no-gutters mt-4">
    <?php
    foreach (range(1, 12) as $value) {
        label_text('Campo ' . $value, 'campo', 1);
    }
    foreach (range(1, 6) as $value) {
        label_text('Campo ' . $value, 'campo', 2);
    }
    foreach (range(1, 6) as $value) {
        label_textarea('', 'campo', 2);
    }
    foreach (range(1, 6) as $value) {
        label_textarea('AAAAA', 'campo', 2);
    }
    foreach (range(1, 3) as $value) {
        label_combo('AAAAA', 'campo', [], [], 2);
    }
    foreach (range(1, 3) as $value) {
        label_button('AAAAA', 'campo', 2);
    }
    foreach (range(1, 3) as $value) {
        label_text('AAAAA', 'campo', 2);
    }
    foreach (range(1, 3) as $value) {
        label_button('AAAAA', 'campo', 2);
    }
    foreach (range(1, 6) as $value) {
        $rnd = 2510.15;
        label_money('Campo ' . $value, 'campo', $value, "value='$rnd'", 2);
    }
    ?>
</form>