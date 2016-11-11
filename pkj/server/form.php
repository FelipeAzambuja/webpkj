<?php
//color = default, primary, success, info, warning, danger, link
function row() {
    if (conf::$pkj_row) {
        echo "<div class=\"row\">";
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
    conf::$pkj_uid_comp++;
    $html = "<input type='button' data-button='true' value='$texto' $plus />";
    echo div($html, $size);
}

function radio($id, $texto, $grupo, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = '';
    }
    conf::$pkj_uid_comp++;
    $html = "<label style='cursor:pointer;margin-top:5px' data-radio='true' ><input id='$id' type='radio' name='$grupo' value='$texto'  />  {$texto}</label>";
    echo div($html, $size);
}

function check($id, $texto, $plus = "", $size = 3) {
    if (is_numeric($plus)) {
        $size = $plus;
        $plus = '';
    }
    conf::$pkj_uid_comp++;
    $html = "<label  for='$id' style='margin-top:5px'><input id='$id' type='checkbox' $plus  />  {$texto}</label>";
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
    $retorno .= "</select><script>try{ $('select').chosen();$( '.chosen-container-single' ).css( 'width' , '100%' ); }catch(e){  }</script>";
    echo div($retorno, $size,4);
}

function label($texto = "", $size = 2) {
    conf::$pkj_uid_comp++;
    $retorno = "<div data-label='true' style='line-height:35px' >$texto</div>";
    echo div($retorno, $size);
}

function text($id, $plus = "", $size = 3) {
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
    $html = "<input type='tel' id='{$id}'  data-calendar='true' {$plus} />";
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

function div($elemento = "", $tamanho = "2",$padding=5) {
    conf::$pkj_uid_comp++;
    if ($elemento == "") {
        return "<div class='col_$tamanho col-sm-$tamanho pkjdiv' style='padding-top:{$padding}px;padding-bottom:{$padding}px;height:45px' >$elemento</div>\n";
    } else {
        return "<div class='col_$tamanho col-sm-$tamanho pkjdiv' style='padding-top:{$padding}px;padding-bottom:{$padding}px;height:45px' >$elemento</div>\n";
    }
}

function hidden($id, $value = "", $show = false) {
    echo "<input type='hidden' id='{$id}' value='$value' />" . (($show) ? $value : "");
}