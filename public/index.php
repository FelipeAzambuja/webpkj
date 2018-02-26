<?php
$template = 'templates/template.php';
$title = 'Combo';
function init(){
    setComboData("#usuarios",['a','b'],['A','B']);
    
}
?>
<form>
    <label class="w-100">Usuários
        <select class="form-control" name="" id="usuarios"></select>
    </label>
</form>