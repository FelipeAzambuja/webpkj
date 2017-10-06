<?php
include 'pkjall.php';
$home = replace(__DIR__, "\\", "/");
$home = replace($home, "/pkj/server", "");
$home = basename($home);
ob_start();
include "../../".replace($_SERVER["REQUEST_URI"],"/".$home,"public").".php";

if (isset($_POST["CMD"])) {
    ob_clean();
    include "pkjbind.php";
}else{
    echo ob_get_clean();
}