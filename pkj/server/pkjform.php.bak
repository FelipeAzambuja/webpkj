<?php

//color = default, primary, success, info, warning, danger, link

function row($class = "row", $id = "") {
    if (conf::$pkj_row) {
        echo "<div id=\"{$id}\" class=\"{$class}\">";
        conf::$pkj_row = false;
    } else {
        echo "</div>";
        conf::$pkj_row = true;
    }
}


function button($texto, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = '';
    }
    if (indexof($plus, 'class="') === -1) {
        $plus .= 'class="btn btn-primary form-control"';
    }
    conf::$pkj_uid_comp++;
    $html = "<input type='button' data-button='true' value='$texto' $plus />";
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
function radio($id, $texto, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = '';
    }
    $value = "value='$texto'";
    if (contains($plus, "value")) {
        $value = "";
    }
    conf::$pkj_uid_comp++;
    $html = "<label style='cursor:pointer;margin-top:5px' data-radio='true' ><input name='$id' $plus type='radio'  $value  />  {$texto}</label>";
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
function check($id, $texto,  $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = '';
    }
    conf::$pkj_uid_comp++;

    $idREF = "pkj" . conf::$pkj_uid_comp;
    $value = "value = '$texto'";

    if (contains($plus, "value")) {
        $value = ""; //melhor ficar quieto
    }
    $html = "<label onclick='$(\"input[data-pkj-id=\\\"{$idREF}\\\"]\").trigger(\"click\")'  style='margin-top:5px'><input data-pkj-id='$idREF' name='$id'  type='checkbox' $value $plus  />  {$texto}</label>";
    echo div($html, $size);
}

function combo($id, $itens, $valoresItens = array(), $plus = "", $size = 3) {
    //a unica sobrecarga do php é a do meu saco
    if (is_numeric($valoresItens)) {
        $size = $valoresItens;
        $valoresItens = array();
        $plus = '';
    }
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = '';
    }
    if (indexof($plus, 'class="') === -1) {
        $plus .= 'class="form-control"';
    }
    conf::$pkj_uid_comp++;
    $retorno = "";
    $retorno .= "<select data-combo='true' name='{$id}' $plus >";
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


function text($id, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    if (indexof($plus, 'class="') === -1) {
        $plus .= 'class="form-control"';
    }
    if (indexof($plus, "type") == -1) {
        $html = "<input name='{$id}' type='text' $plus />";
    } else {
        $html = "<input name='{$id}' $plus />";
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
    if (indexof($plus, 'class="') === -1) {
        $plus .= 'class="form-control"';
    }
    if (indexof($plus, "type") == -1) {
        $html = "<input type='text' name='{$id}' data-mask='{$mask}' {$plus} />";
    } else {
        $html = "<input name='{$id}' data-mask='{$mask}' {$plus} />";
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
    if (indexof($plus, 'class="') === -1) {
        $plus .= 'class="form-control"';
    }
    conf::$pkj_uid_comp++;
    $html = "<input type='text' name='{$id}'  data-calendar='true' {$plus} />";
    echo div($html, $size);
}

function number($id, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    if (indexof($plus, 'class="') === -1) {
        $plus .= 'class="form-control"';
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
    if (indexof($plus, 'class="') === -1) {
        $plus .= 'class="form-control"';
    }
    conf::$pkj_uid_comp++;
    $html = "<input type='tel' name='{$id}' data-money='true' {$plus} />";
    echo div($html, $size);
}

function password($id, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    if (indexof($plus, 'class="') === -1) {
        $plus .= 'class="form-control"';
    }
    conf::$pkj_uid_comp++;
    $html = "<input name='{$id}' type='password' data-text='true' $plus />";
    echo div($html, $size);
}

function auto($id, $autocomplete = array('Voce esqueceu de passar o array animal'), $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    if (indexof($plus, 'class="') === -1) {
        $plus .= 'class="form-control"';
    }
    conf::$pkj_uid_comp++;
    $json = json_encode($autocomplete);
    $html = "<input type='text' name='{$id}' data-autocomplete='{$json}' $plus />";
    echo div($html, $size);
}

//precisa atualizar
function upload($id, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = "";
    }
    if (indexof($plus, 'class="') === -1) {
        $plus .= 'class="form-control"';
    }
    conf::$pkj_uid_comp++;
    $r = "<input $plus type='file' name='{$id}' $plus />";
    echo div($r, $size);
}


function div($elemento = "", $tamanho = 2, $class = "") {
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
    echo "<input type='hidden' name='{$id}' value='$value' />" . (($show) ? $value : "");
}
