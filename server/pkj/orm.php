<?php

// O nome das classes deve ter sempre a primeira maiuscula
//     function getRelations() {
//        $retorno = array();
//        $retorno[] = array("Contatos.pessoa" => "id");
//        $retorno[] = array("Config.pessoa" => "id");
//        return $retorno;
//    }
//    function getFields() {
//        $campos = array();
//        $campos[] = array("name" => "id", "type" => "integer");
//        $campos[] = array("name" => "tipo", "type" => "text");
//        $campos[] = array("name" => "valor", "type" => "text");
//        $campos[] = array("name" => "pessoa", "type" => "integer");
//        return $campos;
//    }
//    function getName() {
//        return "contatos";
//    }
//    nunca esqueça que deve declarar as variaveis  e definir os campos
//    NUNCA DECLARE GET
class DBTable {

    //autocomplete
    public function getPK() {
        return "id";
    }

    //autocomplete
    public function getName() {

    }

    //autocomplete
    public function getType($name) {
        if ($name == $this->getPK()) {
            return "integer";
        } else if (startswith($name, "number_")) {
            if (conf::$servidor == "sqlite") {
                return "numeric";
            } else {
                return "float";
            }
        } else if (startswith($name, "date_")) {
            return "datetime";
        } else if (startswith($name, "moment_")) {
            if (conf::$servidor == "sqlite") {
                return "timestamp default (datetime('now','localtime'))";
            } else {
                return "timestamp default CURRENT_TIMESTAMP";
            }
        } else {
            return "text";
        }
    }

    //autocomplete
    public function getRelations() {

    }

    public function getFields() {
        $r = array();
        $vars = array_keys(get_object_vars($this));
        foreach ($vars as $v) {
            //remover variaveis privadas
            if (!startswith($v, "_")) {
                $tmp = array();
                $tmp["name"] = $v;
                $tmp["type"] = $this->getType($v);
                $r[] = $tmp;
            }
        }
        return $r;
    }

    public function drop() {
        return query("drop table " . $this->getName());
    }

    /**
     * where obrigatorio por que sim hauhauha
     * @param type $where
     * @return query
     */
    public function delete($where) {
        return query("delete from " . $this->getName() . " where $where");
    }

    public function create() {
        $sql = 'create table if not exists ' . $this->getName() . '(';
        $servidor = conf::$servidor;
        $fields = $this->getFields();
        for ($i = 0; $i < count($fields); $i++) {
            $name = $fields[$i]["name"];
            $type = $fields[$i]["type"];
            $pk = "";
            if ($this->getPK() == $name) {
                if ($servidor == "sqlite") {
                    $pk = "primary key autoincrement";
                } else if ($servidor == "mysql") {
                    $pk = "primary key auto_increment";
                } else if ($servidor == "postgre") {
                    $pk = "primary key";
                    $type = "serial";
                }
            }
            if ($i == count($fields) - 1) {
                $sql .= "$name $type $pk";
            } else {
                $sql .= "$name $type $pk,";
            }
        }
        $sql .= ')';
        return query($sql);
    }

    public function begin() {
        begin_transaction();
    }

    public function rollback() {
        rollback_transaction();
    }

    public function commit() {
        commit_transaction();
    }

    public function exists($where) {
        return count($this->query($where)) > 0;
    }

    public function update($where) {
        $campos = $this->getFields();
        $sql = array();
        for ($index = 0; $index < count($campos); $index++) {
            $campo = $campos[$index];
            $v = null;
            eval('$v = $this->' . $campo["name"] . ';');
            if ($v !== null) {
                $sql[$campo["name"]] = $v;
            }
        }
        if (query(SQLupdate($this->getName(), $sql, $where))) {
            return true;
        } else {
            throw new Exception(bd_get_error());
        }
    }

    public function replace($where) {
        $campos = $this->getFields();
        $sql = array();
        for ($index = 0; $index < count($campos); $index++) {
            $campo = $campos[$index];
            $v = null;
            eval('$v = $this->' . $campo["name"] . ';');
            if ($v !== null) {
                $sql[$campo["name"]] = $v;
            }
        }
        if (query(SQLreplace($this->getName(), $sql, $where))) {
            return true;
        } else {
            throw new Exception(bd_get_error());
        }
    }

    public function save() {
        $campos = $this->getFields();
        $sql = array();
        for ($index = 0; $index < count($campos); $index++) {
            $campo = $campos[$index];
            $v = null;
            eval('$v = $this->' . $campo["name"] . ';');
            $sql[$campo["name"]] = $v;
        }
//        alert(SQLinsert($this->getName(), $sql));
        if (query(SQLinsert($this->getName(), $sql))) {
            return intval(oneCol("select id from " . $this->getName() . " order by id desc limit 1"));
        } else {
            throw new Exception(bd_get_error());
        }
    }

    public function __call($name, $arguments) {
        $name = replace($name, "get", "");
        $rels = $this->getRelations();
        foreach ($this->getRelations() as $rel) {
            $tmp = array_keys($rel)[0];
            $mouse = explode(".", $tmp);
            $classe = $mouse[0];
            if (isset($mouse[2])) {
                $classe = ucfirst($mouse[2]);
            }
            if (ucase($classe) != ucase($name)) {
                continue;
            }
            $classe = $mouse[0];
            $campo = $mouse[1];
            $ref = $rel[$tmp];
            $miiii = new DBTable();
            eval('$miiii = new ' . $classe . '();');
            $valor = $this->$ref;
            return $miiii->query("$campo = '$valor'");
        }
    }

    public function __get($name) {
        $tmp = new DBTable();
//        echo '$tmp = $this->get' . ucfirst($name) . '();';
        eval('$tmp = $this->get' . ucfirst($name) . '();');
        return $tmp;
    }

    public function query($plus = "", $filter = "") {
        $tableName = $this->getName();
        $retorno = array();
        if (indexof(ucase($plus), "WHERE") < 0) {
            if (len(trim($plus)) > 0) {
                $plus = "where 1=1 and " . $plus;
            }
        }
        foreach (query("select * from $tableName $plus") as $r) {
            $ok = true;
            if ($filter != "") {
                foreach (array_keys(get_object_vars($r)) as $key) {
                    if ($ok) {
                        $ok = $filter($tableName, $key, $r->$key);
                    } else {
                        continue;
                    }
                }
            }

            if (!$ok) {
                continue;
            }
            $tmp = clone $this;
            foreach ($this->getFields() as $field) {
                $chave = $field["name"];
                if (isset($r->$chave)) {
                    $tmp->$chave = $r->$chave;
                }
            }
            $retorno[] = $tmp;
        }
        return $retorno;
    }

}
