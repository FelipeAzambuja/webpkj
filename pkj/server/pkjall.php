<?php

$notpkj = array("phpliteadmin.php");
if (in_array(basename($_SERVER["SCRIPT_NAME"]), $notpkj)) {
    return false;
}
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
include 'pkjsession.php';
$useSmarty = true;
if ($useSmarty) {
    include 'smarty/Smarty.class.php';
}
include "pkjstring.php";
include "pkjconf.php";

include "pkj.php";
if (conf::$endereco !== "") {
//  include "pkjdb.php";
    include "pkjdb_2.php";
    conectar();
}
include "kint/Kint.class.php";
include 'pkjassets.php';
include "pkjform.php";
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
include 'DBTable.php';
foreach (glob(__DIR__ . "/../db/*.php") as $db):
    include $db;
endforeach;
if (isset($_POST["CMD"])) {
    include "pkjbind.php";
}
