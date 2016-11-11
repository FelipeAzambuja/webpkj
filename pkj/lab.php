<?php
function ler($form){
	sd($form);
    alert(session_get($form["chave"]));
}
function gravar($form){
    session_set($form["chave"], $form["valor"]);
}
