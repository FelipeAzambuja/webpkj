<?php

function begin_transaction() {
    $sql = "";
    switch (conf::$servidor) {
        case "odbc":
            odbc_autocommit(conf::$pkj_bd_sis_conexao, FALSE);
            break;
        case "adodb":
        case "mssql":
            $sql = "BEGIN TRANSACTION";
            break;
        case "mysql":
            $sql = "BEGIN WORK";
            break;
        case "postgre":
        case "sqlite":
            $sql = "BEGIN";
            break;
    }
    query($sql);
}

function commit_transaction() {
    if (conf::$servidor != "odbc") {
        query("COMMIT");
    } else {
        odbc_commit(conf::$pkj_bd_sis_conexao);
        odbc_autocommit(conf::$pkj_bd_sis_conexao, TRUE);
    }
}

function back_transaction(){
  rollback_transaction();
}

function rollback_transaction() {
    if (conf::$servidor != "odbc") {
        query("ROLLBACK");
    } else {
        odbc_rollback(conf::$pkj_bd_sis_conexao);
        odbc_autocommit(conf::$pkj_bd_sis_conexao, TRUE);
    }
}

function conectar() {
    if (conf::$pkj_bd_sis_conexao != null) {
        return true;
    }
    switch (conf::$servidor) {
        case "adodb":
            $conexao = new COM("adodb.connection");
            $conexao->open(conf::$endereco);
            conf::$pkj_bd_sis_conexao = $conexao;
            break;
        case "odbc":
            conf::$pkj_bd_sis_conexao = odbc_connect(conf::$endereco, conf::$usuario, conf::$senha);
            break;
        case "mysql":
            conf::$pkj_bd_sis_conexao = mysqli_connect(conf::$endereco, conf::$usuario, conf::$senha, conf::$base);
            break;
        case "postgre":
            conf::$pkj_bd_sis_conexao = pg_connect("host=" . conf::$endereco . " user=" . conf::$usuario . " password=" . conf::$senha . " dbname=" . conf::$base);
            break;
        case "mssql":
            conf::$pkj_bd_sis_conexao = mssql_connect(conf::$endereco, conf::$usuario, conf::$senha);
            break;
        case "sqlite":
            try {
                conf::$pkj_bd_sis_conexao = new SQLite3(__DIR__ . '/' . conf::$endereco);
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
            break;
    }
}

function db_get_connection() {
    return conf::$pkj_bd_sis_conexao;
}

function db_get_error() {
    return conf::$lastError;
}

function one($query) {
    if (count($query) < 1) {
        return null;
    } else {
        return $query[0];
    }
}

function oneCol($query, $col = "") {
    if (is_array($query)) {
        return one(col($query, $col));
    } else if (is_string($query)) {
        return one(col(query($query), $col));
    }
}

function col($query, $column) {

    $retorno = array();

    if (count($query) < 1) {
        return $retorno;
    }
    if ($column == "") {
        if (is_object($query[0])) {
            $column = array_keys(get_object_vars($query[0]));
        } else {
            $column = array_keys($query[0]);
        }
        $column = $column[0];
    }

    if (is_object($query[0])) {
        foreach ($query as $sis) {
            $retorno[] = $sis->{$column};
        }
    } else {
        foreach ($query as $sis) {
            $retorno[] = $sis[$column];
        }
    }
    return $retorno;
}

/**
 * Retorna o comando SQL em forma de Objeto
 * @param type $comando
 * @param type $atributos
 * @param boolean $oo
 */
function query($comando, $oo = true) {
    return execute($comando, $oo);
}

/**
 * Retorna o comando SQL em forma de Array
 * @param type $comando
 * @param type $atributos
 * @param boolean $oo
 */
function execute($comando, $oo = false) {
    $comando = str_replace(PHP_EOL, '', $comando);
    $comando = str_replace("\r", '', $comando);
    $comando = str_replace("\n", '', $comando);
    $comando = str_replace("\t", ' ', $comando);
    $comando = trim($comando);
    $select = explode(" ", $comando);
    $isSelect = (strtolower($select[0]) == "select" || strtolower($select[0]) == "pragma") ? true : false;
    $query = false;
    conectar();
    switch (conf::$servidor) {
        case "adodb":
            if (!$isSelect) {
                $sis = conf::$pkj_bd_sis_conexao;
                $query = $sis->execute($comando);
            }
            break;
        case "odbc":
            $query = @odbc_exec(conf::$pkj_bd_sis_conexao, $comando);
            conf::$lastError = odbc_errormsg(conf::$pkj_bd_sis_conexao);
            break;
        case "mysql":
            $query = @mysqli_query(conf::$pkj_bd_sis_conexao, $comando);
            conf::$lastError = mysqli_error(conf::$pkj_bd_sis_conexao);
            break;
        case "postgre":
            $query = @pg_query(conf::$pkj_bd_sis_conexao, $comando);
            conf::$lastError = pg_last_error(conf::$pkj_bd_sis_conexao);
            break;
        case "mssql":
            $query = @mssql_query($comando, conf::$pkj_bd_sis_conexao);
            conf::$lastError = mssql_get_last_message();
            break;
        case "sqlite":
            if ($isSelect) {
                $query = @conf::$pkj_bd_sis_conexao->query($comando);
            } else {
                $query = @conf::$pkj_bd_sis_conexao->exec($comando);
            }
            conf::$lastError = conf::$pkj_bd_sis_conexao->lastErrorMsg();
            break;
    }
    $retorno = array();
    if ($isSelect) {
        switch (conf::$servidor) {
            case "adodb":
                $sis = conf::$pkj_bd_sis_conexao;
                $sis2 = new COM("adodb.recordset");
                $sis2->open($comando, $sis);
                $campos = array();
                while (!$sis2->EOF) {
                    if (count($campos) < 1) {
                        foreach ($sis2->fields as $field) {
                            $campos[] = $field->name;
                        }
                    }
                    if ($oo) {
                        while (!$sis2->EOF) {
                            $linha = new stdClass();
                            foreach ($campos as $campo) {
                                $linha->$campo = utf8_encode($sis2[$campo]->value);
                            }
                            $retorno[] = $linha;
                            $sis2->moveNext();
                        }
                    } else {
                        while (!$sis2->EOF) {
                            $linha = array();
                            foreach ($campos as $campo) {
                                $linha[$campo] = utf8_encode($sis2[$campo]->value);
                            }
                            $retorno[] = $linha;
                            $sis2->moveNext();
                        }
                    }
                }
                $sis2->Close();
                break;
            case "odbc":
                if (!$query) {
                    return false;
                }
                while ($row = ($oo) ? @odbc_fetch_object($query) : @odbc_fetch_assoc($query)) {
                    if ($oo) {
                        $sis = new stdClass();
                        foreach (array_keys(get_object_vars($row)) as $col) {
                            $sis->$col = _f_sis1($row->$col);
                        }
                        $row = $sis;
                    } else {
                        $sis = array();
                        foreach (array_keys($row) as $col) {
                            $sis[$col] = _f_sis1($row[$col]);
                        }
                        $row = $sis;
                    }
                    $retorno[] = $row;
                }
                break;
            case "mysql":
                if (!$query) {
                    return false;
                }
                while ($row = ($oo) ? @mysqli_fetch_object($query) : @mysqli_fetch_assoc($query)) {
                    if ($oo) {
                        $sis = new stdClass();
                        foreach (array_keys(get_object_vars($row)) as $col) {
                            $sis->$col = _f_sis1($row->$col);
                        }
                        $row = $sis;
                    } else {
                        $sis = array();
                        foreach (array_keys($row) as $col) {
                            $sis[$col] = _f_sis1($row[$col]);
                        }
                        $row = $sis;
                    }
                    $retorno[] = $row;
                }
                break;
            case "postgre":
                if (!$query) {
                    return false;
                }
                while ($row = ($oo) ? @pg_fetch_object($query) : @pg_fetch_assoc($query)) {
                    if ($oo) {
                        $sis = new stdClass();
                        foreach (array_keys(get_object_vars($row)) as $col) {
                            $sis->$col = _f_sis1($row->$col);
                        }
                        $row = $sis;
                    } else {
                        $sis = array();
                        foreach (array_keys($row) as $col) {
                            $sis[$col] = _f_sis1($row[$col]);
                        }
                        $row = $sis;
                    }
                    $retorno[] = $row;
                }
                break;
            case "mssql":
                if (!$query) {
                    return false;
                }
                while ($row = ($oo) ? @mssql_fetch_object($query) : @mssql_fetch_array($query)) {
                    if ($oo) {
                        $sis = new stdClass();
                        foreach (array_keys(get_object_vars($row)) as $col) {
                            $sis->$col = _f_sis1($row->$col);
                        }
                        $row = $sis;
                    } else {
                        $sis = array();
                        foreach (array_keys($row) as $col) {
                            $sis[$col] = _f_sis1($row[$col]);
                        }
                        $row = $sis;
                    }
                    $retorno[] = $row;
                }
                break;
            case "sqlite":
                if (!$query) {
                    return false;
                }
                if ($oo) {
                    while ($row = $query->fetchArray()) {
                        $obj = new stdClass();
                        foreach ($row as $key => $value) {
                            if (!is_numeric($key)) {
                                $obj->$key = _f_sis1($value);
                            }
                        }
                        $retorno[] = $obj;
                    }
                } else {
                    while ($row = $query->fetchArray()) {
                        if ($oo) {
                            $sis = new stdClass();
                            foreach (array_keys(get_object_vars($row)) as $col) {
                                $sis->$col = _f_sis1($row->$col);
                            }
                            $row = $sis;
                        } else {
                            $sis = array();
                            foreach (array_keys($row) as $col) {
                                $sis[$col] = _f_sis1($row[$col]);
                            }
                            $row = $sis;
                        }
                        $retorno[] = $row;
                    }
                }
                break;
        }
        return $retorno;
    } else {
        return $query;
    }
}

function SQLinsert($tabela, $array) {
    $sql = 'insert into ' . $tabela;
    $tabela = null;
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
    $sql .= ' ( ';
    for ($i = 0; $i < $CountChaves; $i++) {
        if ($i == $CountChaves - 1) {
            $sql.=$chaves[$i];
        } else {
            $sql.=$chaves[$i] . ',';
        }
    }
    $sql .= ' ) values( ';
    $CountChaves = null;
    $chaves = null;
    for ($i = 0; $i < $CountValores; $i++) {
        if ($i == $CountValores - 1) {
            if (ctype_xdigit(replace($valores[$i], '0x', '')) && ucase(left($valores[$i], 2)) == "0X") {
                $sql.= replace($valores[$i], '\'', '');
            } else {
                $sql.= '\'' . replace($valores[$i], '\'', '') . '\'';
            }
        } else {
            if (ctype_xdigit(replace($valores[$i], '0x', '')) && ucase(left($valores[$i], 2)) == "0X") {
                $sql.= replace($valores[$i], '\'', '') . ',';
            } else {
                $sql.= '\'' . replace($valores[$i], '\'', '') . '\',';
            }
        }
    }
    $sql .= ' )';
    $CountValores = null;
    $valores = null;
    return $sql;
}

function SQLreplace($tabela, $array, $where) {
    $sql = '';
    if (count(query(SQLselect($tabela, array("id"), $where))) < 1) {
        $sql = SQLinsert($tabela, $array);
    } else {
        $sql = SQLupdate($tabela, $array, $where);
    }
    return $sql;
}

function SQLinsertorupdate($tabela, $array, $where) {
    return SQLreplace($tabela, $array, $where);
}

function SQLupdate($tabela, $array, $where) {
    $sql = 'update ' . $tabela . ' set ';
    $tabela = null;
    $chaves = array();
    $valores = array();
    foreach ($array as $chave => $valor) {
        if ($valor === null) {
//            continue;
        }
        if ($valor === 'null') {
            $valor = null;
        }
        $chaves[] = $chave;
        $valores[] = $valor;
    }
    $CountChaves = count($chaves);
    $CountValores = count($valores);
    for ($i = 0; $i < $CountChaves; $i++) {
        if ($i == $CountChaves - 1) {
            if (ctype_xdigit(replace($valores[$i], '0x', '')) && ucase(left($valores[$i], 2)) == "0X") {
                $sql.=$chaves[$i] . '= ' . replace($valores[$i], '\'', '');
            } else {
                if ($valores[$i] === null) {
                    $sql.=$chaves[$i] . '= null';
                } else {
                    $sql.=$chaves[$i] . '=\'' . replace($valores[$i], '\'', '') . '\'';
                }
            }
        } else {
            if (ctype_xdigit(replace($valores[$i], '0x', '')) && ucase(left($valores[$i], 2)) == "0X") {
                if ($valores[$i] === null) {
                    $sql.=$chaves[$i] . '= null,';
                } else {
                    $sql.=$chaves[$i] . '=\'' . replace($valores[$i], '\'', '') . '\',';
                }
            } else {
                if ($valores[$i] === null) {
                    $sql.=$chaves[$i] . '= null,';
                } else {
                    $sql.=$chaves[$i] . '=\'' . replace($valores[$i], '\'', '') . '\',';
                }
            }
        }
    }
    $sql .= ' where ' . $where;
    $CountChaves = null;
    $CountValores = null;
    $chaves = null;
    $valores = null;
    return $sql;
}

function SQLdelete($tabela, $where) {
    return 'delete from ' . $tabela . ' where ' . $where;
}

function SQLselect($tabela, $campos, $where) {
    $sql = 'select ';
    $CountCampos = count($campos);
    for ($i = 0; $i < $CountCampos; $i++) {
        if ($i == $CountCampos - 1) {
            $sql.=$campos[$i];
        } else {
            $sql.=$campos[$i] . ',';
        }
    }
    $CountCampos = null;
    $campos = null;
    $sql.=' from ' . $tabela . ' where ' . $where;
    return $sql;
}

function SQLExists($table, $where) {
    return (count(query("select id from $table where $where")) > 0);
}

//Não use isso, seu cachorro pode morrer
function _f_sis1($value) {
    return date_filter($value);
}

?>
