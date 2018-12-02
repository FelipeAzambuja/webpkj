<?php

ini_set('display_errors', 1);
//usando set

if (false) {
    //insert
    $usuario = model_usuarios();
    $usuario->nome = "Felipe";
    $usuario->email = "felipe@newbgp.com.br";
    $usuario->alterado = '26/08/1989';
    $usuario->insert();
}
if (true) {
    //select
    $t1 = model_usuarios()->where('nome', 'Felipe')->get();
    s($t1);
    $t2 = model_usuarios()->where('nome', 'Felipe')->where('email', 'felipe@newbgp.com.br')->get();
    s($t2);
    $t3 = model_usuarios()->where([
                'nome' => 'Felipe'
            ])->get();
    s($t3);
    $t4 = model_usuarios()->where([
                'nome' => 'Felipe',
                'email' => 'felipe@newbgp.com.br'
            ])->get();
    s($t4);
    $t5 = model_usuarios()->where('alterado', '26/08/1989')->get();
    s($t5);
    $t6 = model_usuarios()->where([
                'alterado' => '26/08/1989'
            ])->get();
    s($t6);
    $t7 = model_usuarios()->where([
                ['alterado', '>', '25/08/1989', 'or'],
                ['alterado', '<', '27/08/1989', 'or']
            ])->get();
    s($t7);
    $t8 = model_usuarios()->where([
                ['alterado', '>', '25/08/1989 00:00:00', 'or'],
                ['alterado', '<', '27/08/1989 23:59:59', 'or']
            ])->get();
    s($t8);
}
//update
//select
//delete
