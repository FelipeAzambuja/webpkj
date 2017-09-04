<?php

// O nome das classes deve ter sempre a primeira maiuscula
//     public $id;
//     public $tipo;
//     public $valor;
//     public $pessoa;
//     public $usuario;
//     
//     function getRelations() {
//        $retorno = array();
//        $retorno[] = array("Contatos.pessoa" => "id");
//        $retorno[] = array("Config.pessoa" => "id");
//        $retorno[] = array("Usuarios.id" => "usuario");
//        return $retorno;
//    }
//    /**
//     *
//     *@return Usuarios
//    */
//    function getUsuarios(){
//	  return parent::$this->getUsuarios();
//    }
//    function getFields() {
//        $campos = array();
//        $campos[] = array("name" => "id", "type" => "integer");
//        $campos[] = array("name" => "tipo", "type" => "text");
//        $campos[] = array("name" => "valor", "type" => "text");
//        $campos[] = array("name" => "pessoa", "type" => "integer");
//        $campos[] = array("name" => "usuario", "type" => "integer");
//        return $campos;
//    }
//    function getName() {
//        return "contatos";
//    }
//    nunca esqueça que deve declarar as variaveis  e definir os campos
//    NUNCA DECLARE GET sem colocar o parent::
//class DBTable {
//
//    public function tableInfo($tableName = "") {
//        if ($tableName == "") {
//            $tableName = $this->getName();
//        }
//        $retorno = array();
//        $db = conf::$base;
//        switch (conf::$servidor) {
//
//            case "mysql":
//                $lines = query("desc $tableName");
//                foreach ($lines as $value) {
//                    $tmp = new stdClass();
//                    $tmp->name = $value->Field;
//                    $tmp->type = DBTable::getPKJTYPE($value->Type);
//                    $retorno[] = $tmp;
//                }
//                break;
//            case "postgre":
//                $lines = query("SELECT column_name,data_type FROM information_schema.columns WHERE table_catalog = '$db' and table_name='$tableName'");
//                foreach ($lines as $value) {
//                    c($value);
//                    $tmp = new stdClass();
//                    $tmp->name = $value->column_name;
//                    $tmp->type = DBTable::getPKJTYPE($value->data_type);
//                    $retorno[] = $tmp;
//                }
//                break;
//            case "sqlite":
//                foreach (query("pragma table_info('$tableName')") as $value) {
//                    $tmp = new stdClass();
//                    $tmp->name = $value->name;
//                    $tmp->type = DBTable::getPKJTYPE($value->type);
//                    $retorno[] = $tmp;
//                }
//                break;
//        }
//        return $retorno;
//    }
//
//    private static function getPKJTYPE($type) {
//        switch (lcase($type)) {
//            case "integer":
//            case "int":
//            case "int(11)":
//                return "integer";
//                break;
//            case "double":
//            case "float":
//            case "numeric":
//                return "float";
//                break;
//            case "varchar":
//            case "char":
//            case "string":
//            case "text":
//            case "longtext":
//                return "text";
//                break;
//            case "blob":
//            case "mediumblob":
//            case "bytea":
//            case "longblob":
//                return "blob";
//                break;
//            case "date":
//                return "date";
//                break;
//            case "datetime":
//            case "timestamp":
//            case "timestamp without time zone":
//                return "datetime";
//                break;
//            default:
//                return "text";
//                break;
//        }
//    }
//
//    public function fromArray($array) {
//        foreach (col($this->getFields(), "name") as $field) {
//            $this->{$field} = $array[$field];
//        }
//    }
//
//    public function fromObject($obj) {
//        foreach (col($this->getFields(), "name") as $field) {
//            $this->{$field} = $obj->{$field};
//        }
//    }
//
//    public function __toString() {
//        return $this->{$this->getPK()};
//    }
//
//    //autocomplete
//    public function getPK() {
//        return "id";
//    }
//
//    //autocomplete
//    public function getName() {
//        
//    }
//
//    public function getDeclaredType($name) {
//        if (in_array($name, col($this->getFields(), "name"))) {
//            $i = array_search($name, col($this->getFields(), "name"));
//            return $this->getFields()[$i]["type"];
//        }
//        return $this->getType($name);
//    }
//
//    //autocomplete
//    public function getType($name) {
//        if ($name == $this->getPK()) {
//            return "integer";
//        } else if (startswith($name, "number_")) {
//            if (conf::$servidor == "sqlite") {
//                return "numeric";
//            } else {
//                return "float";
//            }
//        } else if (startswith($name, "date_")) {
//            return "datetime";
//        } else if (startswith($name, "moment_")) {
//            if (conf::$servidor == "sqlite") {
//                return "timestamp default (datetime('now','localtime'))";
//            } else {
//                return "timestamp default CURRENT_TIMESTAMP";
//            }
//        } else {
//            return "text";
//        }
//    }
//
//    //autocomplete
//    public function getRelations() {
//        
//    }
//
//    public function getFields() {
//        $r = array();
//        $vars = array_keys(get_object_vars($this));
//        foreach ($vars as $v) {
//            //remover variaveis privadas
//            if (!startswith($v, "_")) {
//                $tmp = array();
//                $tmp["name"] = $v;
//                $tmp["type"] = $this->getType($v);
//                $r[] = $tmp;
//            }
//        }
//        return $r;
//    }
//
//    public function drop() {
//        return query("drop table " . $this->getName());
//    }
//
//    /**
//     * where obrigatorio por que sim hauhauha
//     * @param type $where
//     * @return query
//     */
//    public function delete($where) {
//        return query("delete from " . $this->getName() . " where $where");
//    }
//
//    public function create() {
//        if (table_exists($this->getName())) {
////            c("A tabela " . $this->getName() . " existe");
//            $dbFields = $this->tableInfo();
//            $tableName = $this->getName();
//            $fields = $this->getFields();
//            $servidor = conf::$servidor;
//            for ($i = 0; $i < count($fields); $i ++) {
//                $name = $fields[$i]["name"];
//                $type = $fields[$i]["type"];
//                
//                if (($type === "blob" || $type === "image") && $servidor === "mysql") {
//                    $type = "mediumblob";
//                }
//                if (($type === "blob" || $type === "image") && $servidor === "postgre") {
//                    $type = "bytea";
//                }
//                if ($type === "datetime" && $servidor === "postgre") {
//                    $type = "timestamp";
//                }
//                $create = !in_array($name, col($dbFields, "name"));
//                if ($create) {
//                    switch (conf::$servidor) {
//                        case "postgre":
//                            $sql = "ALTER TABLE $tableName ADD COLUMN $name $type";
//                            break;
//                        case "mysql":
//                            $sql = "ALTER TABLE $tableName ADD $name $type";
//                            break;
//                        case "sqlite":
//                            $sql = "ALTER TABLE $tableName ADD $name $type";
//                            break;
//                    }
////                    c(" sql  = $sql");
//                    query($sql);
//                }
//            }
//            return;
//        }
//
//        $sql = 'create table if not exists ' . $this->getName() . '(';
//        $servidor = conf::$servidor;
//        $fields = $this->getFields();
//        for ($i = 0; $i < count($fields); $i ++) {
//            $name = $fields[$i]["name"];
//            $type = $fields[$i]["type"];
//            $pk = "";
//            if ($this->getPK() == $name) {
//                if ($servidor == "sqlite") {
//                    $pk = "primary key autoincrement";
//                } else if ($servidor == "mysql") {
//                    $pk = "primary key auto_increment";
//                } else if ($servidor == "postgre") {
//                    $pk = "primary key";
//                    $type = "serial";
//                }
//            }
//            if (($type === "blob" || $type === "image") && $servidor === "mysql") {
//                $type = "mediumblob";
//            }
//            if (($type === "blob" || $type === "image") && $servidor === "postgre") {
//                $type = "bytea";
//            }
//            if ($type === "datetime" && $servidor === "postgre") {
//                $type = "timestamp";
//            }
//            if ($i == count($fields) - 1) {
//                $sql .= "$name $type $pk";
//            } else {
//                $sql .= "$name $type $pk,";
//            }
//        }
//        $sql .= ')';
////    echo $sql;
//        return query($sql);
//    }
//
//    public function begin() {
//        begin_transaction();
//    }
//
//    public function rollback() {
//        rollback_transaction();
//    }
//
//    public function commit() {
//        commit_transaction();
//    }
//
//    public function exists($where) {
//        return count($this->query($where)) > 0;
//    }
//
//    public function update($where) {
//        $campos = $this->getFields();
//        $sql = array();
//        for ($index = 0; $index < count($campos); $index ++) {
//            $campo = $campos[$index];
//            $v = null;
//            eval('$v = $this->' . $campo["name"] . ';');
//            if ($v !== null) {
//                $sql[$campo["name"]] = $v;
//            }
//        }
//        $query = query(SQLupdate($this->getName(), $sql, $where));
//        if (!$query) {
//            $this->create();
//            $query = query(SQLupdate($this->getName(), $sql, $where));
//        }
//        if ($query) {
//            return true;
//        } else {
//            throw new Exception(db_get_error());
//        }
//    }
//
//    public function replace($where) {
//        $campos = $this->getFields();
//        $sql = array();
//        for ($index = 0; $index < count($campos); $index ++) {
//            $campo = $campos[$index];
//            $v = null;
//            eval('$v = $this->' . $campo["name"] . ';');
//            if ($v !== null) {
//                $sql[$campo["name"]] = $v;
//            }
//        }
//        $query = query(SQLreplace($this->getName(), $sql, $where));
//        if (!$query) {
//            $this->create();
//            $query = query(SQLreplace($this->getName(), $sql, $where));
//        }
//        if ($query) {
//            return true;
//        } else {
//            throw new Exception(db_get_error());
//        }
//    }
//
//    function _getDefault($field) {
//        $campos = $this->getFields();
//        for ($index = 0; $index < count($campos); $index++) {
//            if($campos[$index]["name"] == $field){
//                if(isset($campos[$index]["default"])){
//                    $d = $campos[$index]["default"];
//                    switch (gettype($d)) {
//                        case "string":
//                            return $d;
//                            break;
//                        case "object":
//                            return $d();
//                            break;
//                        default:
//                            return $d;
//                            break;
//                    }
//                }
//            }
//        }
//        return null;
//    }
//    public function save() {
//        $campos = $this->getFields();
//        $sql = array();
//        for ($index = 0; $index < count($campos); $index ++) {
//            $campo = $campos[$index];
//            $v = null;
//            eval('$v = $this->' . $campo["name"] . ';');
//            if($v == null){
//                $v = $this->_getDefault($campo["name"]);
//            }
//            $sql[$campo["name"]] = $v;
//        }
////        alert(SQLinsert($this->getName(), $sql));
//        $query = query(SQLinsert($this->getName(), $sql));
//        if (!$query) {
//            $this->create();
//            $query = query(SQLinsert($this->getName(), $sql));
//        }
//        if ($query) {
//            return intval(oneCol("select id from " . $this->getName() . " order by id desc limit 1"));
//        } else {
//            throw new Exception(db_get_error());
//        }
//    }
//
//    public function __call($name, $arguments) {
//        if (startswith($name, "set")) {
//            $servidor = conf::$servidor;
//            $name = replace_first(lcase($name), "set", "");
//            $type = $this->getDeclaredType($name);
//            switch ($type) {
//                case "image":
//                case "blob":
//                    if ($servidor === "postgre") {
//                        if ($arguments[0] !== null) {
//                            $this->{$name} = pg_escape_bytea($arguments[0]);
//                        } else {
//                            $this->{$name} = 'null';
//                        }
//                    }
//                    break;
//
//                default:
//                    break;
//            }
//            return $this;
//        }
//        $name = replace($name, "get", "");
//        $rels = $this->getRelations();
//        foreach ($this->getRelations() as $rel) {
//            $tmp = array_keys($rel)[0];
//            $mouse = explode(".", $tmp);
//            $classe = $mouse[0];
//            if (isset($mouse[2])) {
//                $classe = ucfirst($mouse[2]);
//            }
//            if (ucase($classe) != ucase($name)) {
//                continue;
//            }
//            $classe = $mouse[0];
//            $campo = $mouse[1];
//            $ref = $rel[$tmp];
//            $miiii = new DBTable();
//            eval('$miiii = new ' . $classe . '();');
//            $valor = $this->$ref;
//            return $miiii->query("$campo = '$valor'");
//        }
//    }
//
//    public function __get($name) {
//        $tmp = new DBTable();
////        echo '$tmp = $this->get' . ucfirst($name) . '();';
//        eval('$tmp = $this->get' . ucfirst($name) . '();');
//        return $tmp;
//    }
//
//    /**
//     * 
//     * @param string $plus where
//     * @param type $filter filtros por function
//     * @return static[]|static|array 
//     */
//    public function one($plus = "", $filter = "") {
//        return one($this->query($plus, $filter));
//    }
//
//    /**
//     * 
//     * @param string $plus where
//     * @param type $filter filtros por function
//     * @return static[]|static|array 
//     */
//    public function query($plus = "", $filter = "") {
//        $tableName = $this->getName();
//        $retorno = array();
//        if (indexof(ucase($plus), "WHERE") < 0) {
//            if (len(trim($plus)) > 0) {
//                $plus = "where 1=1 and " . $plus;
//            }
//        }
//        $fiedsName = implode(",", col($this->getFields(), "name"));
//        $sql = "select $fiedsName from $tableName $plus";
//        $servidor = conf::$servidor;
//        $query = query($sql);
//        if (!$query) {
//
//            $this->create();
//            $query = query($sql);
//        }
//        foreach ($query as $r) {
//            $ok = true;
//            if ($filter != "") {
//                foreach (array_keys(get_object_vars($r)) as $key) {
//                    if ($ok) {
//                        $ok = $filter($tableName, $key, $r->$key);
//                    } else {
//                        continue;
//                    }
//                }
//            }
//
//            if (!$ok) {
//                continue;
//            }
//            $tmp = clone $this;
//            foreach ($this->getFields() as $field) {
//                $chave = $field["name"];
//                $type = $field["type"];
//                if (isset($r->$chave)) {
//                    $tmp->$chave = $r->$chave;
//                    //conversão de tipos do get
//                    switch ($type) {
//                        case "blob":
//                            if ($servidor === "postgre") {
//                                $tmp->$chave = pg_unescape_bytea($tmp->$chave);
//                            }
//                            break;
//                        case "image":
//                            if ($servidor === "postgre") {
//                                $tmp->$chave = pg_unescape_bytea($tmp->$chave);
//                                $tmp->$chave = "data:image;base64," . base64_encode($tmp->$chave);
//                            } elseif ($servidor === "mysql") {
//                                $tmp->$chave = "data:image;base64," . base64_encode($tmp->$chave);
//                            } else {
//                                $tmp->$chave = "data:image;base64," . base64_encode($tmp->$chave);
//                            }
//                            break;
//                    }
//                }
//            }
//            $retorno[] = $tmp;
//        }
//        return $retorno;
//    }
//
//}
