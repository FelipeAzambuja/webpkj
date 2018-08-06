<?php
ini_set('display_errors',1);
if (count(model_usuarios()->get()) < 1) {
    foreach (range(1, 100) as $value) {
        $usuario = model_usuarios();
        $usuario->nome = 'usuario ' . $value;
        $usuario->senha = md5('123');
        $usuario->insert();
    }
}
foreach (model_usuarios()->get( ) as $value) {
    s($value->nome);
}
