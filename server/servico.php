<?php
include './pkj/all.php';


function teste($form) {
    $bind = bind($form);
    
    bind($form)->setValue("nome", 'Felip"\'""""\'e');
//    echo json_encode($form);
}
