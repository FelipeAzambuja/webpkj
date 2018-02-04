<?php

//$select = sql()->table('pessoas')->select()->get();
//dd($select);
//$select = sql()->table('pessoas')->where([
//            'nome' => 'Felipe',
//        ])->join('contatos', 'pessoa', 'id')->select()->get();
//dd($select);
//exit();
//echo (sql()->prepare_value_data(file_get_contents('felix.jpg')));

$data = [];
$imagem = file_get_contents('felix.jpg');
for ($index = 0; $index < 100; $index++) {
    $data[] = [
        'nome' => 'Contador ' . $index,
        'foto' => $imagem
    ];
}
s(sql()->table('pessoas')->insert($data, 1000));
echo(sql()->db->last_error);
dd(sql()->table('pessoas')->insert([
            'nome' => 'Teste'
]));
exit();
$select = sql()->table('pessoas')->where([
            'nome' => 'Felipe',
        ])->join(sql()->table('contatos')->where('importante', '1'), 'pessoa', 'id')->select()->start();
dd($select);
