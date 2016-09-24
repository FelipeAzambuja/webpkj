<?php
function session_get($campo){
 error_reporting(0);
 ob_start();
 session_start();
 $retorno = $_SESSION[$campo];
 error_reporting(-1);
 return $retorno;
}

function session_set($campo,$valor){
 error_reporting(0);
 ob_start();
 session_start();
 $_SESSION[$campo] = $valor;
 error_reporting(-1);
}

function session_kill(){
 error_reporting(0);
 session_start();
 session_unset();
 session_destroy();
 error_reporting(-1);
}

function session_info(){
 error_reporting(0);
 session_start();
 $retorno = session_status();
 error_reporting(-1);
 return $retorno;
}

?>
