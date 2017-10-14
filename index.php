<?php

include 'pkj/server/pkjall.php';
$ex_uri = explode('/', $_SERVER["REQUEST_URI"]);
$concat = false;
$path = "";
$base= basename(__DIR__);
for ($index = 0; $index < count($ex_uri); $index++) {
    if($ex_uri[$index] == $base ){
        $concat = true;
        $path .= "public"  ;
        continue;
    }
    if($concat){
      $path .= "/".$ex_uri[$index]  ;
    }
}

ob_start();
//$f = replace($_SERVER["REQUEST_URI"], "/" . $home, "/public");

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