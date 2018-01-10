<?php

//color = default, primary, success, info, warning, danger, link
function _haveOnsen() {
    return in_array("onsen", Resource::$list);
}

function row($class = "") {
    if (conf::$pkj_row) {
        echo "<div class=\"row {$class}\">";
        conf::$pkj_row = false;
    } else {
        echo "</div>";
        conf::$pkj_row = true;
    }
}

function ons_row() {

    if (conf::$pkj_row) {
        echo "<ons-row style='text-align:center'>";
        conf::$pkj_row = false;
    } else {
        echo "</ons-row>";
        conf::$pkj_row = true;
    }
}

function button($texto, $plus = "", $size = 3) {
    if (_haveOnsen()) {
        return ons_button($texto, $plus, $size);
    }
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = '';
    }
    conf::$pkj_uid_comp++;
    $html = "<input type='button' data-button='true' value='$texto' $plus />";
    echo div($html, $size);
}

function ons_button($texto, $plus = "", $size = "") {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = '';
    }
    conf::$pkj_uid_comp++;
    $html = "<ons-button style='width:100%' value='$texto' $plus >$texto</ons-button>";
    echo div($html, $size);
}

/**
 * 
 * @param type $id identificação
 * @param type $texto Texto
 * @param type $grupo Agrupamento 
 * @param type $plus atributos adicionais
 * @param type $size colunas
 * @return type
 */
function radio($id, $texto, $grupo = "", $plus = "", $size = 3) {
    if ($grupo === "" || is_numeric($grupo)) {
        if (is_numeric($grupo)) {
            $plus = $grupo;
        }
        $grupo = $id;
    }
    if (_haveOnsen()) {
        return ons_radio($id, $texto, $grupo, $plus, $size);
    }
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = '';
    }
    $value = "value='$texto'";
    if (contains($plus, "value")) {
        $value = "";
    }
    conf::$pkj_uid_comp++;
    $html = "<label style='cursor:pointer;margin-top:5px' data-radio='true' ><input id='$id' $plus type='radio' name='$grupo' $value  />  {$texto}</label>";
    echo div($html, $size);
}

function ons_radio($id, $texto, $grupo, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = '';
    }
    conf::$pkj_uid_comp++;
    $value = "value='$texto'";
    if (contains($plus, "value")) {
        $value = "";
    }
    ob_start();
    ?>
    <label class="left">
        <ons-input <?= $plus ?> type="radio" name="<?= $grupo ?>" <?= $value ?> id='<?= "pkj" . conf::$pkj_uid_comp . "_" . $id ?>' input-id="<?= $id ?>"></ons-input>
    </label>
    <label onclick='$("#<?= "pkj" . conf::$pkj_uid_comp . "_" . $id ?>").trigger("click")' for="<?= "pkj" . conf::$pkj_uid_comp . "_" . $id ?>" class="center">
    <?= $texto ?>
    </label>
    <?php
    $html = ob_get_clean();
    echo div($html, $size);
}

function ons_check($id, $texto, $grupo = "", $plus = "", $size = 3) {
    if (is_numeric($grupo)) {
        $size = $grupo;
        $grupo = "";
    }
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = '';
    }
    conf::$pkj_uid_comp++;
    ob_start();
    ?>
    <label class="left">
        <ons-input <?= $plus ?>  <?= ($grupo !== "") ? "name='$grupo'" : "" ?>  type="checkbox" value='<?= $texto ?>' id='<?= "pkj" . conf::$pkj_uid_comp . "_" . $id ?>' input-id="<?= $id ?>"></ons-input>
    </label>
    <label onclick='$("#<?= "pkj" . conf::$pkj_uid_comp . "_" . $id ?>").trigger("click")'  for="<?= "pkj" . conf::$pkj_uid_comp . "_" . $id ?>" class="center">
    <?= $texto ?>
    </label>
    <?php
    $html = ob_get_clean();
    echo div($html, $size);
}

/**
 * Cria um input tipo checkbox
 * @param type $id identificador
 * @param type $texto Texto
 * @param type $grupo Para agrupar em array, se vazio envia 'true' ou 'false'
 * @param type $plus atributos adicionais
 * @param type $size tamanho da coluna
 * @return type
 */
function check($id, $texto, $grupo = "", $plus = "", $size = 3) {
    if (_haveOnsen()) {
        return ons_check($id, $texto, $grupo, $plus, $size);
    }
    if (startswith($grupo, "checked")) {
        $grupo = "";
        $plus = "checked = 'true'";
    }
    if (is_numeric($grupo)) {
        $size = $grupo;
        $grupo = "";
    }
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = '';
    }
    conf::$pkj_uid_comp++;
    if ($grupo !== "") {
        $grupo = " name='$grupo' ";
    }
    $idREF = "pkj" . conf::$pkj_uid_comp;
    $value = "value = '$texto'";

    if (contains($plus, "value")) {
        $value = ""; //melhor ficar quieto
    }
    $html = "<label onclick='$(\"input[data-pkj-id=\\\"{$idREF}\\\"]\").trigger(\"click\")'  style='margin-top:5px'><input data-pkj-id='$idREF' id='$id' $grupo type='checkbox' $value $plus  />  {$texto}</label>";
    echo div($html, $size);
}

function combo($id, $itens, $valoresItens = array(), $plus = "", $size = 3) {
    //a unica sobrecarga do php �� a do meu saco
    if (is_numeric($valoresItens)) {
        $size = $valoresItens;
        $valoresItens = array();
        $plus = '';
    }
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = '';
    }
    conf::$pkj_uid_comp++;
    $retorno = "";
    $retorno .= "<select id='$id' data-combo='true' name='{$id}' $plus >";
    $contador = 0;
    foreach ($itens as $value) {
        if (count($valoresItens) > 0):
            $retorno .= "<option value='" . $value . "'>" . $valoresItens[$contador] . "</option>";
        else:
            $retorno .= "<option value='" . $value . "'>" . $value . "</option>";
        endif;

        $contador++;
    }
    $retorno .= "</select>";
//    $retorno .= "</select><script>try{ $('select').chosen();$( '.chosen-container-single' ).css( 'width' , '100%' ); }catch(e){  }</script>";
    echo div($retorno, $size, 4);
}

function label($texto = "", $size = 1) {
    conf::$pkj_uid_comp++;
    $retorno = "<label data-label='true' class='control-label' style='line-height:35px' >$texto</label>";
    echo div($retorno, $size);
}

function ons_input($id, $plus = "", $size = "") {

    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    if (indexof($plus, "type") == -1) {
        $html = "<ons-input input-id='" . $id . "' style='width:100%' name='{$id}'  type='text' $plus />";
    } else {
        $html = "<ons-input input-id='" . $id . "' style='width:100%' name='{$id}' $plus />";
    }
    conf::$pkj_uid_comp++;
    echo div($html, $size);
}

function text($id, $plus = "", $size = 3) {
    if (_haveOnsen()) {
        return ons_input($id, $plus, $size);
    }
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    if (indexof($plus, "type") == -1) {
        $html = "<input id='" . $id . "' name='{$id}' data-text='true' type='text' $plus />";
    } else {
        $html = "<input id='" . $id . "' data-text='true' name='{$id}' $plus />";
    }
    conf::$pkj_uid_comp++;
    echo div($html, $size);
}

/**
 * Inicia uma mascara 
 * @param type $id Indetifica����o do componente
 * @param String $mask Mascara
 * @return String componente
 */
function mask($id, $mask = "999999", $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    if (indexof($plus, "type") == -1) {
        $html = "<input type='text' id='{$id}' data-mask='{$mask}' {$plus} />";
    } else {
        $html = "<input id='{$id}' data-mask='{$mask}' {$plus} />";
    }
    conf::$pkj_uid_comp++;

    echo div($html, $size);
}

/**
 * Inicia uma data
 * @param type $id Indetifica����o do componente
 * @return String componente
 */
function calendar($id, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    conf::$pkj_uid_comp++;
    $html = "<input type='text' id='{$id}'  data-calendar='true' {$plus} />";
    echo div($html, $size);
}

function number($id, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    conf::$pkj_uid_comp++;
    $html = "<input type='tel' id='{$id}' data-number='true' {$plus} />";
    echo div($html, $size);
}

function money($id, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    conf::$pkj_uid_comp++;
    $html = "<input type='tel' id='{$id}' data-money='true' {$plus} />";
    echo div($html, $size);
}

function password($id, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    conf::$pkj_uid_comp++;
    $html = "<input id='" . $id . "' name='{$id}' type='password' data-text='true' $plus />";
    echo div($html, $size);
}

function auto($id, $autocomplete = array('Voce esqueceu de passar o array animal'), $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    conf::$pkj_uid_comp++;
    $json = json_encode($autocomplete);
    $html = "<input type='text' id='{$id}' data-autocomplete='{$json}' $plus />";
    echo div($html, $size);
}

function upload($id, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    conf::$pkj_uid_comp++;
    $r = "<input $plus type='file' id='{$id}' data-upload='true' $plus />";
    echo div($r, $size);
}

function ons_col($elemento = "", $tamanho = "") {
    if (len($tamanho) == 0) {
        return "<ons-col style='padding:5px'>$elemento</ons-col>";
    } else {
        return "<ons-col width='$tamanho' style='padding:5px'>$elemento</ons-col>";
    }
}

function div($elemento = "", $tamanho = 2, $class = "") {
//    if (in_array("onsen", Resource::$list)) {
//        if(is_numeric($tamanho)){
//            $tamanho = ($tamanho * 10) . "%";
//        }
//        return ons_col($elemento, $tamanho);
//    }
    conf::$pkj_uid_comp++;
    return "<div class='col-md-$tamanho $class pkjdiv' style='min-height:35px' >$elemento</div>\n";
}

function label_text($label, $id, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    ob_start();
    label($label, 12);
    text($id, $plus, 12);
    $html = ob_get_clean();
    echo div($html, $size, "form-group");
}

function label_combo($label, $id, $itens = [], $valoresItens = [], $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    ob_start();
    label($label, 12);
    combo($id, $itens, $valoresItens, $plus, 12);
    $html = ob_get_clean();
    echo div($html, $size, "form-group");
}

function label_upload($label, $id, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    ob_start();
    label($label, 12);
    upload($id, $plus, 12);
    $html = ob_get_clean();
    echo div($html, $size, "form-group");
}

function label_password($label, $id, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    ob_start();
    label($label, 12);
    password($id, $plus, 12);
    $html = ob_get_clean();
    echo div($html, $size, "form-group");
}

function label_money($label, $id, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    ob_start();
    label($label, 12);
    money($id, $plus, 12);
    $html = ob_get_clean();
    echo div($html, $size, "form-group");
}

function label_calendar($label, $id, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    ob_start();
    label($label, 12);
    calendar($id, $plus, 12);
    $html = ob_get_clean();
    echo div($html, $size, "form-group");
}

function label_mask($label, $id, $mask = "9999", $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    ob_start();
    label($label, 12);
    mask($id, $mask, $plus, 12);
    $html = ob_get_clean();
    echo div($html, $size, "form-group");
}

function label_number($label, $id, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    ob_start();
    label($label, 12);
    number($id, $plus, 12);
    $html = ob_get_clean();
    echo div($html, $size, "form-group");
}

function hidden($id, $value = "", $show = false) {
    echo "<input type='hidden' id='{$id}' value='$value' />" . (($show) ? $value : "");
}
