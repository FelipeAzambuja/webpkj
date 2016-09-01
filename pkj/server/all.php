<?php

date_default_timezone_set('America/Sao_Paulo'); //or change to whatever timezone you want
ini_set("output_buffering", "0");

function show_errors($v = true) {
    if ($v) {
        ini_set("display_errors", "1");
        error_reporting(-1);
    } else {
        ini_set("display_errors", "0");
        error_reporting(0);
    }
}

include 'session.php';
include "string.php";
include "conf.php";
include "pkj.php";
include "db.php";
include "kint/Kint.class.php";
include 'assets.php';
if (conf::$quick) {
    include 'quick.php';
}
$resource = resource();
if (conf::$servidor == "mysql") {
    query("SET NAMES 'utf8'");
    query('SET character_set_connection=utf8');
    query('SET character_set_client=utf8');
    query('SET character_set_results=utf8');
}
include "orm.php";
foreach (glob(__DIR__."/../db/*.php") as $db):
    include $db;
endforeach;
include "bind.php";
