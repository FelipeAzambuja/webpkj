<?php

show_errors(true);

if (false) {
    //insert
    $usuario = model_usuarios();
    $usuario->nome = "Felipe";
    $usuario->email = "felipe@newbgp.com.br";
    $usuario->alterado = '26/08/1989';
    $usuario->insert();
}
if (true) {
    //insert
    model_usuarios()->fromArray([
        'nome' => 'Felipe',
        'email' => 'felipe@newbgp.com.br',
        'alterado' => '26/08/1989'
    ])->insert();
}
if (false) {
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
if (false) {
    echo 'testando o update';
    $usuario = model_usuarios()->where('id', 102)->first();
    $usuario->email = 'gatinho2@gmail.com.br';
    if (!$usuario->update()) {
        echo db()->last_error;
    }
}
if (false) {
    $usuario = model_usuarios()->where('id', 102)->fromArray([
                'email' => 'felipe@gmail.com.br3'
            ])->update();
    $usuario = model_usuarios()->fromArray([
                'email' => 'felipe@gmail.com.br4'
            ])->where('id', 102)->update();
}



if (false) {
    $usuario = model_usuarios()->byId(102);
    $usuario->email = 'gatinho@gmail.com.br2';
    $usuario->update();
}

//delete
if (true) {
    model_usuarios()->where('id',104)->delete();
}
