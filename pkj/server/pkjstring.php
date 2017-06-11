<?php

function lcase($texto) {
    return strtolower($texto);
}

function ucase($texto) {
    return strtoupper($texto);
}

function camel($texto) {
    return ucfirst(lcase($texto));
}

function replace($texto, $procura, $valor) {
    return str_replace($procura, $valor, $texto);
}

function contains($texto, $valor) {
    return (strpos($texto, $valor) === false) ? false : true;
}
function left($texto, $tamanho) {
     return substr($texto, 0, $tamanho);
}

function right($texto, $tamanho) {
     return substr($texto, -$tamanho);
}

function indexof($texto, $valor) {
    if (strpos($texto, $valor) === false) {
        return -1;
    } else {
        return strpos($texto, $valor);
    }
}

function lastindexof($texto, $valor) {
    if (strrpos($texto, $valor) === false) {
        return -1;
    } else {
        return strrpos($texto, $valor);
    }
}

function len($texto) {
    return strlen($texto);
}

function substring($texto, $inicio, $quantidade = -1) {
    if ($quantidade == -1) {
        $quantidade = len($texto) - $inicio;
    }
    return substr($texto, $inicio, $quantidade);
}
/**
 * 
 * @param type $haystack long text
 * @param type $needle 
 * @return type
 */
function startswith($haystack, $needle) {
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function endswith($haystack, $needle) {
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}
function replace_first($subject,$search, $replace) {
    return implode($replace, explode($search, $subject, 2));
}
