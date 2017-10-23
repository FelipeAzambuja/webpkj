<?php

include 'pkj/server/pkjall.php';
$template = '';
ob_start();
$path = pkj_get_home(__DIR__);
$url = $path;
$path = 'public/'.replace($_SERVER["REQUEST_URI"],$path,'');
$path = replace($path,'?'.$_SERVER["QUERY_STRING"],'');
if (endswith($path, "public/")) {
    $path = "public/index";
}
$path .= '.php';
if (!file_exists($path)) {
    $path = "public/err_404.php";
}

include $path;

if (isset($_POST["CMD"])) {
    ob_clean();
    include "pkj/server/pkjbind.php";
} else {
    if($template !== ''){
        $content = ob_get_clean();
        include "public/".$template;
        exit();
    }
    echo ob_get_clean();
}