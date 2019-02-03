<?php
$template = 'templates/template.php';

function tabela_aleatoria($form) {
    ob_start();
    ?>
    <table class="table datatables datatables-responsive" width="100%">
        <thead>
            <tr>
                <th>Header</th>
                <th>Header2</th>
                <th>Header2</th>
                <th>Header2</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (range(0, random_int(0, 1000)) as $i): ?>
                <tr>
                    <td><?= random_int(0, 999) ?></td>
                    <td><?= random_int(0, 999) ?></td>
                    <td><?= random_int(0, 999) ?></td>
                    <td><?= random_int(0, 999) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
    </table>
    <?php
    $html = ob_get_clean();
    html('#tabela', $html);
    setTimeout('tabela_aleatoria', 1000 * 0.2);
}
?> 

<form class="row no-gutters mt-4" init="tabela_aleatoria()">
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
<div id="tabela"></div>