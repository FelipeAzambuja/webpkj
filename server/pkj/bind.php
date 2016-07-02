<?php

$bindDebug = false;
if ($bindDebug) {
    error_reporting(-1);
    ini_set("display_errors", "On");
}


$noUse = get_defined_functions();
if (isset($_POST["CMD"])) {
    $cmd = $_POST["CMD"];
    if (in_array($cmd, $noUse)) {
        if ($bindDebug) {
            console . log("função proibida");
        }
        exit();
    }
    $tmp2 = $_POST;
    addslashes_array($tmp2);
    unset($tmp2["CMD"]);
    if ($tmp2["post0"] === "") {
        unset($tmp2["post0"]);
    }
    ob_start();
    call_user_func($cmd, $tmp2);
    $contents = addslashes(ob_get_contents());
    ob_end_clean();
    echo "try { $cmd('$contents'); } catch (e) {  }";
    exit();
}
