<?php

include 'pkj/server/pkjall.php';
ob_start();
$path = pkj_get_home(__DIR__);
$path = 'public/'.replace($_SERVER["REQUEST_URI"],$path,'');
if (endswith($path, "public/")) {
    $path = "public/index";
}
$path .= '.php';
if (!file_exists($path)) {
    echo "Arquivo não encontrado $path ";
    exit();
}

include $path;

if (isset($_POST["CMD"])) {
    ob_clean();
    include "pkj/server/pkjbind.php";
} else {
    echo ob_get_clean();
}