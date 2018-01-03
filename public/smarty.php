<?php

function teste2($form) {
    alert($form["nome"]);
}

$view['pessoas'] = db()->select('pessoas', '', 'limit 100');
