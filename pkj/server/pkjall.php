<?php

register_shutdown_function(function () {
    if (function_exists('db')) {
        db()->db = null;
    }
});
//$notpkj = array("phpliteadmin.php");
//if (in_array(basename($_SERVER["SCRIPT_NAME"]), $notpkj)) {
//    return false;
//}
if (isset($_POST["HOST"])) {
    header("Access-Control-Allow-Credentials:true");
    if ($_POST["HOST"] !== "file://") {
        header("Access-Control-Allow-Origin: " . $_POST["HOST"]);
    } else {
        header("Access-Control-Allow-Origin: *");
    }
}

function show_errors($v = true) {
    if ($v) {
        ini_set("display_errors", "1");
        error_reporting(-1);
    } else {
        ini_set("display_errors", "0");
        error_reporting(0);
    }
}

show_errors();
include 'Undefined.php';
include 'pkjsession.php';
$useSmarty = false;
if ($useSmarty) {
    include 'smarty/Smarty.class.php';
}
include 'pkjstring.php';
include 'pkjconf.php'; //

include 'pkj.php';
if (conf::$endereco !== "") {
//  include "pkjdb.php";
    include 'pkjdb_2.php';
    conectar();
}
include 'kint/Kint.class.php';
include 'pkjassets.php';
include 'pkjform.php';
if (conf::$quick) {
    include 'pkjquick.php';
}
//$resource = resource ();
//if ( conf::$servidor == "mysql" && conf::$endereco != "" ) {
//  query ( "SET NAMES 'utf8'" );
//  query ( 'SET character_set_connection=utf8' );
//  query ( 'SET character_set_client=utf8' );
//  query ( 'SET character_set_results=utf8' );
//}
//include "pkjorm.php";
if (is_dir(realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "orm"))) {
    include 'ORM.php';
    require_all(realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "orm"));
}

include 'Debug.php';
//foreach (glob(__DIR__ . "/../../orm/*/*.php") as $db):
//    include $db;
//endforeach;

