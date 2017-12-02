<?php 
set_time_limit(60);
$data = [];
$pessoas = [];
foreach(range(1,10000) as $i){
    $pessoa = new stdClass();
    $pessoa->id = $i;
    $pessoa->nome = 'Felipe Nunes Azambuja';
    $pessoas[]= $pessoa;
}
foreach ($pessoas as $d) {
    $data["data"][] = [
        $d->id,
        $d->nome
    ];
}
echo json_encode($data);