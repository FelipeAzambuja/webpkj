<?php

class Db {

    /**
     *
     * @var PDO
     */
    public $pdo;
    public $last_error;
    public $last_sql;
    public $last_parameters;
    public $fetch;
    public $db;
    public $driver;

    function __construct($servidor, $endereco = null, $base = null, $usuario = null, $senha = null) {

        $this->db = $base;
        if ($servidor === "postgre") {
            $servidor = "pgsql";
        }
        $this->driver = $servidor;
        if (!in_array($servidor, array("mysql", "firebird", "odbc", "pgsql", "sqlite", "sqlsrv"))) {
            echo "servidor invalido";
            exit();
        }
        try {
            if ($servidor === "sqlite") {
                $endereco = __DIR__ . '/' . $endereco;
                $this->pdo = new PDO("sqlite:{$endereco}", null, null, array(
                    PDO::ATTR_PERSISTENT => true
                ));
            } else {
                $this->pdo = new PDO("{$servidor}:host={$endereco};dbname={$base}" . (($servidor === "mysql") ? ";charset=UTF8" : ""), $usuario, $senha);
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        } finally {
            
        }
    }

    // <editor-fold defaultstate="collapsed" desc="transactions">

    function begin_transaction() {
        return $this->pdo->beginTransaction();
    }

    function commit_transaction() {
        return $this->pdo->commit();
    }

    function back_transaction() {
        return $this->rollback_transaction();
    }

    function rollback_transaction() {
        return $this->pdo->rollBack();
    }

// </editor-fold>

    /**
     * last insert id
     * @param string $table 
     * @return integer
     */
    public function last_insert_id($table) {
        return oneCol($this->query("select max(id) from {$table}"));
    }

    /**
     * 
     * @param string $table
     * @return boolean
     */
    function table_exists($table) {
        return (count($this->table_fields($table)) > 0);
    }

    /**
     * 
     * @param string $sql
     * @param array $parameters
     * @return array
     */
    function query($sql, $parameters = array()) {
        $p = $this->statement($sql, $parameters);
        if (!$p) {
            return false;
        }
        if ($this->fetch === null) {
            $this->fetch = PDO::FETCH_OBJ;
        }
        return $p->fetchAll($this->fetch);
    }

    function is_multibyte($s) {
        return mb_strlen($s, 'utf-8') < strlen($s);
    }

    function one($sql, $parameters) {
        return one($this->query($sql, $parameters));
    }

    function insert($table, $values) {
        $sql = "insert into {$table} ";
        $sql .= "(" . implode(",", array_keys($values)) . ") values";
        $p = [];
        for ($index = 0; $index < count($values); $index++) {
            $p[] = "?";
        }
        $sql .= "(" . implode(",", $p) . ") ";
        return $this->query($sql, array_values($values));
    }

    function update($table, $values, $where) {
        $sql = "update {$table} set ";
        $p = [];
        foreach ($values as $key => $value) {
            $p[] = " {$key} = ? ";
        }
        $sql .= implode(",", $p);
        $sql .= " where " . $this->where($where);
        return $this->query($sql, array_values($values));
    }

    function insert_or_update($table, $values, $where) {
        if ($this->exists($table, $where)) {
            return $this->update($table, $values, $where);
        } else {
            return $this->insert($table, $values);
        }
    }

    /**
     * @param string table
     * @param array|string where
     */
    function exists($table, $where) {
        return $this->query("select id from {$table} where " . $this->where($where) . " limit 1") > 0;
    }

    function delete($table, $where) {
        return $this->query("delete from {$table} where " . $this->where($where));
    }

    function select($table, $where, $plus = "") {
        $sql = "select * from {$table} where " . $this->where($where) . " {$plus}";
        return $this->query($sql);
    }

    function where($where) {
        if (is_string($where)) {
            if (trim($where) === "") {
                return "1=1";
            } else {
                return $where;
            }
        }
        if (is_array($where)) {
            //remover nulls
            if (is_array_numeric($where)) {
                for ($index1 = 0; $index1 < count($where); $index1++) {
                    if ($where[$index1] === null) {
                        array_slice($where, $index1);
                    }
                }
            } else {
                foreach ($where as $key => $value) {
                    if ($value === null) {
                        unset($where[$key]);
                    }
                }
            }

            if (count($where) > 0) {
                if (is_array(array_keys($where))) {
                    //["nome"=>"a"],["senha"=>"123"]
                    $list = [];
                    foreach ($where as $key => $value) {
                        if ($this->is_multibyte($value)) {
                            $value = $this->pdo->quote($value, PDO::PARAM_LOB);
                        } else {
                            $value = $this->pdo->quote($value, PDO::PARAM_STR);
                        }
                        $list[] = "{$key}={$value}";
                    }
                    return implode(" AND ", $list);
                } elseif (is_int(array_keys($where))) {
                    //0=>["nome","=","a"],1 = ["senha","=","123"]
                    $list = [];
                    for ($index = 0; $index < count($where); $index++) {
                        $jesse = $where[$index];
                        if ($this->is_multibyte($jesse[2])) {
                            $jesse[2] = $this->pdo->quote($jesse[2], PDO::PARAM_LOB);
                        } else {
                            $jesse[2] = $this->pdo->quote($jesse[2], PDO::PARAM_STR);
                        }
                        $list[] = $where[0] . "" . $where[1] . "" . $where[2];
                    }
                    return implode(" AND ", $list);
                } else {
                    return "1=1";
                }
            } else {
                return "1=1";
            }
        } else {
            return $where;
        }
    }

    /**
     * 
     * @param string $sql
     * @param array $parameters
     * @return PDOStatement
     */
    function statement($sql, $parameters = array()) {
//        $sql = str_replace(PHP_EOL, '', $sql);
//        $sql = str_replace("\r", '', $sql);
//        $sql = str_replace("\n", '', $sql);
//        $sql = str_replace("\t", ' ', $sql);
//        $sql = trim($sql);
        $this->last_sql = $sql;
        $this->last_parameters = $parameters;
        $p = $this->pdo->prepare($sql);
        $c = 1;
//		s($sql);
        foreach ($parameters as $key => $value) {

            if ($this->is_multibyte($value)) {
                if (is_numeric($key)) {
                    $p->bindValue($c, $value, PDO::PARAM_LOB);
                }else{
                    $p->bindValue($key, $value, PDO::PARAM_LOB);
                }
            } else {
                if (is_numeric($key)) {
                    $p->bindValue($c, $value, PDO::PARAM_STR);
                }else{
                    $p->bindValue($key, $value, PDO::PARAM_STR);
                }
            }
            $c++;
        }
        if (!$p->execute()) {
//            var_dump($p->errorInfo());
            $info = $p->errorInfo();
            $this->last_error = "{$info[0]}:{$info[1]}:{$info[2]}:$sql:";
            return false;
        }
        return $p;
    }

    function database_tables($db = null) {
        if ($db === null) {
            $db = $this->db;
        }
        $sql = "";
        switch (conf::$servidor) {
            case "mysql":
                $sql = "SELECT information_schema.TABLES.TABLE_NAME AS NAME FROM information_schema.TABLES WHERE information_schema.TABLES.TABLE_SCHEMA='{$db}'";
                break;
//            case "firebird":
//                $sql = "SELECT 'not work' as 'NAME'";
//                break;
            case "pgsql":
                $sql = "SELECT TABLE_NAME AS NAME FROM information_schema.TABLES WHERE TABLE_CATALOG='{$db}' AND TABLE_TYPE='BASE TABLE'";
                break;
            case "sqlite":
                $sql = "SELECT name as NAME FROM sqlite_master WHERE type='table'";
                break;
            case "sqlsrv":
                $sql = "select TABLE_NAME AS NAME from information_schema.TABLES where TABLE_CATALOG='{$db}' and TABLE_TYPE='BASE TABLE'";
                break;
            default:
                $sql = "select TABLE_NAME AS NAME from information_schema.TABLES where TABLE_CATALOG='{$db}' and TABLE_TYPE='BASE TABLE'";
                break;
        }
        $r = $this->query($sql);
        for ($index = 0; $index < count($r); $index++) {
            $r[$index]->name = (isset($r[$index]->name)) ? $r[$index]->name : $r[$index]->NAME;
        }
        return col($r, "name");
    }

    /**
     * Essa função pode aumentar o tamanho do banco de dados para o mysql
     * @param string $type
     * @param string $driver
     * @return string
     */
    function detranslate_field($type, $driver = null) {
        if ($driver === null) {
            $driver = $this->driver;
        }
        if (strpos($type, "|")) {
            $type = explode("|", $type)[0];
        }
        if (class_exists($type)) {
            return "integer";
        }
        switch ($type) {
            case "integer":
            case "serial":
            case "int":
                return "integer";
                break;
            case "float"://outros
            case "double"://nunca usado ??? 
            case "double precision"://pg
            case "number"://sqlite
            case "numberic"://sqlite 
                if ($driver === "sqlite") {
                    return "numeric";
                } else {
                    return "float";
                }
                break;
            case "string":
            case "text":
                return "text";
                break;
            case "file":
            case "blob":
            case "bytea":
            case "mediumblob":
            case "smallblob":
            case "longblob":
            case "image":
                if ($driver === "pgsql") {
                    return "bytea";
                } else {
                    return "longblob";
                }
                break;
            case "date":
                return "datetime";
                break;
            case "time":
                return "datetime";
                break;
            case "timestamp":
            case "datetime":
                return "datetime";
                break;
            default:
                //varchar
                return "text";
                break;
        }
    }

    public function get_pk_auto($driver = null) {
        if ($driver === null) {
            $driver = $this->driver;
        }
        switch ($driver) {
            case "mysql":
                $sql = "primary key auto_increment";
                break;
//            case "firebird":
//                $sql = "SELECT FIRST 1 * FROM {$table}";
//                break;
            case "pgsql":
                $sql = "primary key"; //usar serial
                break;
            case "sqlite":
                $sql = "primary key autoincrement";
                break;
            case "sqlsrv":
                $sql = "identity primary key";
                break;
            default:
                $sql = "primary key";
                break;
        }
        return $sql;
    }

    public function get_current_timestamp($driver = null) {
        if ($driver === null) {
            $driver = $this->driver;
        }
        switch ($driver) {
            case "mysql":
                $sql = "current_timestamp";
                break;
//            case "firebird":
//                $sql = "SELECT FIRST 1 * FROM {$table}";
//                break;
            case "pgsql":
                $sql = "current_timestamp"; //usar serial
                break;
            case "sqlite":
                $sql = "datetime('now','localtime')";
                break;
            case "sqlsrv":
                $sql = "identity primary key";
                break;
            default:
                $sql = "current_timestamp";
                break;
        }
        return $sql;
    }

    /**
     * 
     * @param string $type
     * @return string
     */
    function translate_field($type) {
        switch (lcase($type)) {
            case "integer":
            case "serial":
            case "int":
                return "integer";
                break;
            case "float"://outros
            case "double"://nunca usado ??? 
            case "double precision"://pg
            case "number"://sqlite
            case "numberic"://sqlite 
                return "float";
                break;
            case "string":
            case "text":
                return "string";
                break;
            case "file":
            case "blob":
            case "bytea":
            case "mediumblob":
            case "smallblob":
            case "longblob":
            case "image":
                return "blob";
                break;
            case "date":
                return "date";
                break;
            case "time":
                return "time";
                break;
            case "timestamp":
            case "datetime":
                return "datetime";
                break;
            default:
                //varchar
                return "string";
                break;
        }
    }

    /**
     * 
     * @param string $table
     * @return array
     */
    function table_fields($table) {
        $sql = "";
        $db = $this->db;
        switch ($this->driver) {
            case "mysql":
                $sql = "select COLUMN_NAME as 'name', DATA_TYPE as 'type' from information_schema.COLUMNS WHERE TABLE_schema='{$db}' and TABLE_NAME='{$table}'";
                break;
//            case "firebird":
//                $sql = "SELECT FIRST 1 * FROM {$table}";
//                break;
            case "pgsql":
                $sql = "select COLUMN_NAME as name, DATA_TYPE as type from information_schema.COLUMNS WHERE TABLE_CATALOG='{$db}' and TABLE_NAME='{$table}'";
                break;
            case "sqlite":
                $sql = "pragma table_info({$table})";
                break;
            case "sqlsrv":
                $sql = "select COLUMN_NAME AS name, DATA_TYPE AS type from INFORMATION_SCHEMA.COLUMNS WHERE TABLE_CATALOG='{$db}' AND TABLE_NAME='{$table}'";
                break;
            default:
                $sql = "select COLUMN_NAME as 'name', DATA_TYPE as 'type' from information_schema.COLUMNS WHERE TABLE_schema='{$db}' and TABLE_NAME='{$table}'";
                break;
        }
        $s = $this->query($sql);
        for ($index = 0; $index < count($s); $index++) {
            $s[$index]->dbtype = $s[$index]->type;
            $s[$index]->type = $this->translate_field($s[$index]->type);
            $s[$index]->detype = $this->detranslate_field($s[$index]->type, $this->driver);
        }
        return $s;
    }

}

function begin_transaction() {
    db_get_connection()->begin_transaction();
}

function commit_transaction() {
    db_get_connection()->commit_transaction();
}

function back_transaction() {
    db_get_connection()->back_transaction();
}

function rollback_transaction() {
    db_get_connection()->rollback_transaction();
}

function conectar() {
    if (conf::$pkj_bd_sis_conexao != null) {
        return true;
    }
    conf::$pkj_bd_sis_conexao = new Db(conf::$servidor, conf::$endereco, conf::$base, conf::$usuario, conf::$senha);
}

/**
 * 
 * @return Db
 */
function db_get_connection() {
    return conf::$pkj_bd_sis_conexao;
}

/**
 * 
 * @return Db
 */
function db() {
    return conf::$pkj_bd_sis_conexao;
}

function db_get_error() {
    return conf::$pkj_bd_sis_conexao->last_error;
}

function one($query) {
    if (is_object($query)) {
        return $query;
    }
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

  /**
 * Retorna o comando SQL em forma de Array
 * @param type $comando
 * @param type $atributos
 * @param boolean $oo
 */
function query($comando, $p = array()) {
    conectar();
    return conf::$pkj_bd_sis_conexao->query($comando, $p);
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
            $sql .= $chaves[$i];
        } else {
            $sql .= $chaves[$i] . ',';
        }
    }
    $sql .= ' ) values( ';
    $CountChaves = null;
    $chaves = null;
    for ($i = 0; $i < $CountValores; $i++) {
        if ($i == $CountValores - 1) {
            if (ctype_xdigit(replace($valores[$i], '0x', '')) && ucase(left($valores[$i], 2)) == "0X") {
                $sql .= replace($valores[$i], '\'', '');
            } else {
                $sql .= '\'' . replace($valores[$i], '\'', '') . '\'';
            }
        } else {
            if (ctype_xdigit(replace($valores[$i], '0x', '')) && ucase(left($valores[$i], 2)) == "0X") {
                $sql .= replace($valores[$i], '\'', '') . ',';
            } else {
                $sql .= '\'' . replace($valores[$i], '\'', '') . '\',';
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
                $sql .= $chaves[$i] . '= ' . replace($valores[$i], '\'', '');
            } else {
                if ($valores[$i] === null) {
                    $sql .= $chaves[$i] . '= null';
                } else {
                    $sql .= $chaves[$i] . '=\'' . replace($valores[$i], '\'', '') . '\'';
                }
            }
        } else {
            if (ctype_xdigit(replace($valores[$i], '0x', '')) && ucase(left($valores[$i], 2)) == "0X") {
                if ($valores[$i] === null) {
                    $sql .= $chaves[$i] . '= null,';
                } else {
                    $sql .= $chaves[$i] . '=' . replace($valores[$i], '\'', '') . ',';
                }
            } else {
                if ($valores[$i] === null) {
                    $sql .= $chaves[$i] . '= null,';
                } else {
                    $sql .= $chaves[$i] . '=\'' . replace($valores[$i], '\'', '') . '\',';
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
            $sql .= $campos[$i];
        } else {
            $sql .= $campos[$i] . ',';
        }
    }
    $CountCampos = null;
    $campos = null;
    $sql .= ' from ' . $tabela . ' where ' . $where;
    return $sql;
}

function SQLExists($table, $where = null) {
    if ($where == null) {
        $sql = "select id from $table limit 1";
    } else {
        $sql = "select id from $table where $where";
    }
    $r = query($sql);
    return (count($r) > 0);
}

function table_exists($name) {
    $base = conf::$base;
    switch (conf::$servidor) {
        case "postgre":
            $sql = "SELECT * FROM information_schema.tables  where table_catalog='$base' and table_name='$name'";
            break;
        case "mysql":
            $sql = "SELECT * FROM `COLUMNS` WHERE TABLE_NAME='$name' AND TABLE_SCHEMA='$base'";
            break;
        case "sqlite":
            $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name='$name'";
            break;
        default:
            $sql = "select * from $name limit 1";
            break;
    }

    $r = query($sql);
    return count($r) > 0;
}

function table_fields($name) {
    return db_get_connection()->table_fields($name);
}

//Não use isso, seu cachorro pode morrer
function _f_sis1($value) {
    return date_filter($value);
}

?>
