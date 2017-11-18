<?php 
set_time_limit(60);
$data = [];
foreach (db()->query("select id,nome from pessoas limit 10000") as $d) {
    $data["data"][] = [
        $d->id,
        $d->nome
    ];
}
echo json_encode($data);