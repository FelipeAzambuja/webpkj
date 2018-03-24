<?php

register_shutdown_function(function () {
    if (conf::$endereco !== '') {
        db()->db = null;
    }
});
//ini_set('output_buffering', 0);
//ini_set('date.timezone', 'America/Sao_Paulo');
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

function run_forever($v = true) {
    if ($v) {
        set_time_limit(0);
        ignore_user_abort(true);
        ini_set('memory_limit', '-1');
    } else {
        set_time_limit(1);
        ignore_user_abort(false);
        ini_set('memory_limit', '-1');
    }
}

//show_errors();
include 'Undefined.php';

include 'pkjsession.php';
//$useSmarty = false;
//if ($useSmarty) {
//    include 'smarty/Smarty.class.php';
//}
include 'pkjstring.php';
include 'pkjconf.php'; //


include 'vendor/autoload.php';

include 'pkj.php';
//if (conf::$endereco !== "") {
//  include "pkjdb.php";
include 'SQL.php';
include 'pkjdb_2.php';
//    conectar();
//}
//ainda não é urgência , porém precisa refatorar
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
//if (is_dir(realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "orm"))) {
//    include 'ORM.php';
//    require_all(realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "orm"));
//}

if (is_dir(realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "model"))) {
    include 'Model.php';
    require_all(realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "model"));
}
include 'Debug.php';
//foreach (glob(__DIR__ . "/../../orm/*/*.php") as $db):
//    include $db;
//endforeach;

