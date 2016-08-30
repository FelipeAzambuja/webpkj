<?php
function import($name){
    resource()->import($name);
}
function alert($msg){
    JS::alert($msg);
}
function console($msg){
    JS::console($msg);
}
function redirect($page,$data=""){
    JS::redirect($page, $data);
}