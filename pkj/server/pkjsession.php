<?php

function session_create_table() {
    if (!table_exists("pkj_session")) {
        $sql = "create table pkj_session(id text,chave text,valor text)";
        query($sql);
    }
}

function session_exists($campo) {
    return (session_get($campo) === null) ? false : true;
}

function session_get($campo) {
    if (conf::$session === "default") {
        error_reporting(0);
//        ob_start();
        @session_start();
        $retorno = $_SESSION[$campo];
        error_reporting(-1);
    } elseif (conf::$session === "javascript") {
        if (!isset($_POST["session"][$campo])) {
            $retorno = null;
        } else {
            $retorno = $_POST["session"][$campo];
        }
    } elseif (conf::$session === "database") {
        $id = session_id();
        if(empty($id)){
            session_start();
        }
        $id = session_id();
        session_create_table();
        $ip = $_SERVER["REMOTE_ADDR"];
        $retorno = oneCol(query("select valor from pkj_session where id='$id' and chave='$campo'"));
    }
    return $retorno;
}

function session_set($campo, $valor) {
    if (conf::$session === "default") {
        error_reporting(0);
        session_start();
        $_SESSION[$campo] = $valor;
        error_reporting(-1);
    } elseif (conf::$session === "javascript") {
        echo "session.{$campo} = '{$valor}';";
    } elseif (conf::$session === "database") {
        session_create_table();
        $ip = $_SERVER["REMOTE_ADDR"];
        $id = session_id();
        if(empty($id)){
            session_start();
        }
        $id = session_id();
        $sql = array();
        $sql["id"] = $id;
        $sql["chave"] = $campo;
        $sql["valor"] = $valor;
        if (SQLExists("pkj_session", "id='$id' and chave='$campo'")) {
            $sql = SQLupdate("pkj_session", $sql, "id='$id' and chave='$campo'");
        } else {
            $sql = SQLinsert("pkj_session", $sql);
        }
        query($sql);
    }
}

function session_kill() {
    error_reporting(0);
    session_start();
    session_unset();
    session_destroy();
    error_reporting(-1);
}

function session_info() {
    error_reporting(0);
    session_start();
    $retorno = session_status();
    error_reporting(-1);
    return $retorno;
}
?>
