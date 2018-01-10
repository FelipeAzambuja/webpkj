<?php

$pessoa = one(orm_pessoas()->select());
$pessoa->nome = 'Pedro';
$pessoa->telefone = '';
sd($pessoa);
