<?php
$user = "felipe";
$pass = "123";
if (!(post("user") === $user && post("pass") === $pass)) {
    sendError("Erro de autenticação");
}
switch (post("method")) {
    case "test":
        $data = array();
        $data["type"] = "data";
        $data["data"] = array("status" => "ok");
        echo json_encode($data);
        break;
    case "execute":
        if (post("parameters") === "") {
            query(post("sql"));
        } else {
            $sql = prepareSQL(post("sql"), post("parameters"));
            query($sql);
        }
        break;
    case "query":
        if (post("parameters") === "") {
            echo json_encode(query(post("sql")));
        } else {
            $sql = prepareSQL(post("sql"), post("parameters"));
            echo json_encode(query($sql));
        }
        break;
    default:
        sendError("Comando não encontrado");
        break;
}

function sendError($message) {
    $data = array();
    $data["type"] = "error";
    $data["message"] = $message;
    echo json_encode($data);
    exit();
}

function prepareSQL($sql, $array) {
    $chaves = array();
    $valores = array();
    foreach ($array as $chave => $valor) {
        if ($valor === null) {
            continue;
        }
        $chaves[] = $chave;
        $valores[] = $valor;
    }
    $CountChaves = count($chaves);
    $CountValores = count($valores);
    for ($i = 0; $i < $CountValores; $i++) {
        if (ctype_xdigit(replace($valores[$i], '0x', '')) && ucase(left($valores[$i], 2)) == "0X") {
            $valores[$i] = replace($valores[$i], '\'', '');
        } else {
            $valores[$i] = '\'' . replace($valores[$i], '\'', '') . '\'';
        }
    }
    for ($i = 0; $i < $CountValores; $i++) {
        $valor = $valores[$i];
        $sql = replace_first($sql, "?", $valor);
    }
    $CountValores = null;
    $valores = null;
    return $sql;
}
