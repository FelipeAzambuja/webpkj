<?php 
set_time_limit(60);
$data = [];
foreach (range(1,1000) as $d) {
    $data["data"][] = [
        $d,
        implode("",range(1,9))
    ];
}
Debug::wait();
echo json_encode($data);