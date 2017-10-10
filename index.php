<?php

include 'pkj/server/pkjall.php';
$home = replace(__DIR__, "\\", "/");
$home = replace($home, "/pkj/server", "");
$home = basename($home);

ob_start();
$f = replace($_SERVER["REQUEST_URI"], "/" . $home, "public");
if ($f === 'public/') {
    $f = "public/index";
}
$f .= '.php';
if (!file_exists($f)) {
    echo "Arquivo não encontrado";
    exit();
}

include $f;

if (isset($_POST["CMD"])) {
    ob_clean();
    include "pkj/server/pkjbind.php";
} else {
    echo ob_get_clean();
}