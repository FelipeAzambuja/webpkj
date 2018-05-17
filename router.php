<?php

if (!isset($_SERVER["QUERY_STRING"])) {
    $_SERVER["QUERY_STRING"] = '';
}
$ext = explode('.', $_SERVER['REQUEST_URI']);
$ext = $ext[count($ext) - 1];
if (in_array($ext, ['js', 'css', 'png', 'jpg', 'gif'])) {
    if (substr($_SERVER['REQUEST_URI'], 1, 3) === 'pkj') {
//        echo file_get_contents(substr($_SERVER['REQUEST_URI'],1));
        return false;
    } else {
        echo file_get_contents('public' . $_SERVER['REQUEST_URI']);
    }
} else {
    include "index.php";
}

