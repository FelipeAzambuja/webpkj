<?php

//me pergunto o que eu faço da minha vida
class ORM implements JsonSerializable {

    /**
     * @internal description
     * @access private
     * @var string
     */
    public $table_name;
    //name   | type    | dbtype   | detype
    public $fields = [];
    //name   | type (php)
    public $declared_fields = [];

    /**
     *
     * @var Db 
     */
    public $db = null;

    function get_default($name) {
        return false;
    }

    function get_table_name() {
        return "";
    }

    function on_create() {
        
    }

    function on_alter() {
        
    }

    /**
     * 
     * @param string $table_name
     * @param Db $db
     */
    function __construct($table_name = null, $db = null) {
        if ($table_name === null) {
            $table_name = $this->get_table_name();
        }
        $this->table_name = $table_name;
        if ($db === null) {
            $this->db = conf::$pkj_bd_sis_conexao;
        } else {
            $this->db = $db;
        }
        $this->parse_fields();
        $this->create_table();
        //create relation 
        foreach ($this->fields as $f) {
            if ($f->relation[0] !== 'false') {
                $this->{$f->name} = (new ReflectionClass($f->class))->newInstance();
            } else {
                $this->{$f->name} = undefined();
            }
        }
    }

    /**
     * Fill data
     * @param array $form
     */
    public function fromArray($form) {
        $is_a = is_array($form);
        foreach ($this->fields as $f) {
            if ($is_a) {
                if (array_key_exists($f->name, $form)) {
                    $this->{$f->name} = $form[$f->name];
                    //não apagar a foto quando vier null para casos de formulario de updates
                    if (in_array($f->type, ['blob', 'image']) && $form[$f->name] == 'null') {
                        $this->{$f->name} = null;
                        continue;
                    }
                    if (in_array($f->type, ['blob', 'image'])) {
                        $this->{$f->name} = upload_parser($form[$f->name])->getData();
                    }
                    if (in_array($f->type, ['date', 'datetime'])) {
                        $this->{$f->name} = cdate($form[$f->name])->format('Y-m-d H:i:s');
                    }
                }
            } else {
                if (property_exists($form, $f->name)) {
                    $this->{$f->name} = $form{$f->name};
                    //não apagar a foto quando vier null para casos de formulario de updates
                    if (in_array($f->type, ['blob', 'image']) && $this->{$f->name} == 'null') {
                        $this->{$f->name} = null;
                        continue;
                    }
                    if (in_array($f->type, ['blob', 'image'])) {
                        $this->{$f->name} = upload_parser($form->{$f->name})->getData();
                    }
                    if (in_array($f->type, ['date', 'datetime'])) {
                        $this->{$f->name} = cdate($form->{$f->name})->format('Y-m-d H:i:s');
                    }
                }
            }
        }
        return $this;
    }

    /**
     * 
     * @param int $id
     * @return self|static
     */
    function byId($id) {
        $id = intval(trim($id));
        return $this->select([
                    "id" => $id
                ])[0];
    }

    /**
     * 
     * @return int id insert
     */
    function insert() {
        $myNameIs = get_class($this);
        //primeiro verificar se precisa criar os relacionados
        $a = array();
        $objetos_criados = [];
        foreach ($this->fields as $f) {
            $id = (isset($this->{$f->name}->id)) ? $this->{$f->name}->id : 0;
            if ($this->{$f->name} instanceof Undefined || $id instanceof Undefined) {
                continue;
            }
            if ($f->name === "id") {
                continue;
            }
            if (is_array($this->{$f->name})) {

                foreach ($this->{$f->name} as $c) {
                    $tfieldClass = explode(".", $f->relation[1])[0];
//                    $tfieldName = $f->relation[0];
                    $tfieldName = explode(".", $f->relation[1])[1];

                    $objetos_criados[] = [
                        "obj" => $c,
                        "field" => $tfieldName
                    ];
                }
            } else {
                //também tem treta 
                //testar 1 para 1
                if (is_object($this->{$f->name})) {
                    $o = $this->{$f->name};

                    if (!is_numeric($o->id)) {
                        $o = $o->insert();
                    }
                    $a[$f->name] = $o->id;
                } else {
                    $a[$f->name] = $this->{$f->name};
                }
            }
        }

        $i = $this->db->insert($this->table_name, $a);
        if ($i !== false) {
            $last = array();
            foreach ($objetos_criados as $obj) {
                $obj["obj"]->{$obj["field"]} = $this->db->last_insert_id($this->table_name);
//                echo $obj[ "obj" ]->id ;
                if (!is_numeric($obj["obj"]->id)) {

                    $obj["obj"]->insert();
                }
            }
            $last = one($this->select($a, " order by id desc limit 1 "));
            return $last;
        } else {
            return false;
        }
    }

    /**
     * @param array $where
     */
    function delete($where) {
        return $this->db->delete($this->table_name, $where);
    }

    /**
     * @param string where
     * @return boolean 
     */
    function update($where = array()) {
        $values = [];
        foreach ($this->fields as $field) {
            if (!is_undefined($this->{$field->name})) {
                $values[$field->name] = $this->{$field->name};
            }
        }
        if (count($where) < 1) {
            foreach ($this->fields as $key => $value) {
                $v = $this->{$value->name};
                if (!is_undefined($v)) {
                    $where[$value->name] = $v;
                }
            }
        }
        return $this->db->update($this->table_name, $values, $where);
    }

    public function exists($where) {
        return $this->db->exists($this->table_name, $where);
    }

    function insert_or_update($where) {
        if ($this->exists($where)) {
            $values = [];
            foreach ($this->fields as $key => $value) {
                $v = $this->{$value->name};
                if ($v !== null) {
                    $values[$value->name] = $v;
                }
            }
            return $this->update($values, $where);
        } else {
            return $this->insert();
        }
    }

    public function __debugInfo() {
        return json_decode(json_encode($this, JSON_NUMERIC_CHECK), true);
    }

    public static function cast($destination, \stdClass $source) {
        $sourceReflection = new \ReflectionObject($source);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $name = $sourceProperty->getName();
            $destination->{$name} = $source->$name;
        }
        return $destination;
    }

    /**
     * 
     * @param type $where
     * @param type $plus
     * @return self|static|array
     */
    function select($where = "", $plus = "") {
        $me = $this->db->select($this->table_name, $where, $plus);
        for ($index = 0; $index < count($me); $index++) {
            $me[$index] = $this->cast(clone $this, $me[$index]);
            foreach ($this->fields as $tfield) {
                if (isset($tfield->relation)) {
                    if (count($tfield->relation) > 1) {
                        $tfieldClass = explode(".", $tfield->relation[1])[0];
                        $tfieldClassField = explode(".", $tfield->relation[1])[1];
                        $tfieldName = $tfield->relation[0];
                        $obj = (new ReflectionClass($tfieldClass))->newInstance();
                        $retorno = $obj->select([
                            $tfieldClassField => $me[$index]->{$tfieldName}
                        ]);
                        if ($tfieldName !== "id") {
                            $retorno = one($retorno);
                        }
                        $me[$index]->{$tfield->name} = $retorno;
                    }
                }
                if ($tfield->type === 'datetime') {
                    $me[$index]->{$tfield->name} = cdate($me[$index]->{$tfield->name})->format(conf::$dateFormat . ' H:i:s');
                }
                if ($tfield->type === 'date') {
                    $me[$index]->{$tfield->name} = cdate($me[$index]->{$tfield->name})->format(conf::$dateFormat);
                }
                if ($tfield->type === 'time') {
                    $me[$index]->{$tfield->name} = cdate($me[$index]->{$tfield->name})->format('H:i:s');
                }
            }
//            unset($me[$index]->fields);
//            unset($me[$index]->table_name);
//            unset($me[$index]->declared_fields);
//            unset($me[$index]->db);
        }
//        $fromArray = $this->fromArray($me);
        return $me;
    }

    /**
     * 
     * @param string $doc
     * @return object
     */
    private function doc_info($doc) {
        $r = [];
        $doc = explode("\n", $doc);
        foreach ($doc as $l) {
            $l = trim(str_replace(['*', '/'], ['', ''], $l));
            if ($l !== '') {
                $ex = array_filter(explode(' ', $l));
                if (count($ex) > 1) {
                    for ($index = 2; $index < count($ex); $index++) {
                        $ex[1] .= $ex[$index];
                    }
                    $r[$ex[0]] = $ex[1];
                } else {
                    $r[$ex[0]] = true;
                }
            }
        }
        return $r;
    }

    private function get_info($info, $key, $default) {
        foreach ($info as $k => $v) {
            if ($k === $key) {
                return trim($v);
            }
        }
        return $default;
    }

    public function parse_fields() {
        $vars = get_class_vars(get_class($this));
        $parsed = [];
        foreach ($vars as $k => $v) {
            $rv = new ReflectionProperty(get_class($this), $k);
            if ($rv->getDeclaringClass()->name === get_class($this)) {
                $tmp = new stdClass();
                $tmp->name = $rv->getName();
                $tmp->doc = (string) $rv->getDocComment();
                $tmp->info = $this->doc_info($tmp->doc);
                $tmp->type = $this->get_info($tmp->info, '@var', 'string');
                $tmp->dbtype = $this->db->detranslate_field($tmp->type);
                $tmp->lenght = $this->get_info($tmp->info, '@lenght', '0');
                $tmp->pk = $this->get_info($tmp->info, '@pk', 'false');
                $tmp->default = $this->get_info($tmp->info, '@default', '');
                $relation = $this->get_info($tmp->info, '@relation', 'false');
                $tmp->relation = explode("=", $relation);
                $class = $tmp->type;
                if (strpos($class, "|")) {
                    $class = explode("|", $class)[0];
                }
//                s($class);
                if (!class_exists($class)) {
                    $class = false;
                }
                $tmp->class = $class;
                $tmp->comment = $this->get_info($tmp->info, '@comment', '');
                if ($tmp->name === "id" && $tmp->pk === "false") {
                    $tmp->pk = "true";
                    $tmp->type = "integer";
                    if ($this->db->driver === "pgsql") {
                        $tmp->dbtype = "serial";
                    } else {
                        $tmp->dbtype = "int";
                    }
                }
                unset($tmp->doc);
                $parsed[] = $tmp;
            }
        }
        $this->fields = $parsed;
    }

    public function create_table() {
        if (!$this->db->table_exists($this->table_name)) {
            $sql = "create table " . $this->table_name;
            $sql .= "(";
            $fs = [];
            foreach ($this->fields as $f) {
                $fs [] = "{$f->name} {$f->dbtype}" . (( intval($f->lenght) > 0 ) ? "({$f->lenght})" : "") . " " . (($f->pk === "true") ? $this->db->get_pk_auto() : "");
            }
            $sql .= implode(",", $fs);
            $sql .= ")";
            $this->db->query($sql);
            $this->on_create();
        } else {
            $table_fields = $this->db->table_fields($this->table_name);
            foreach ($this->fields as $f) {
                if (!in_array($f->name, col($table_fields, "name"))) {
                    $sql = "alter table {$this->table_name} add column {$f->name} {$f->dbtype}" . (( intval($f->lenght) > 0 ) ? "({$f->lenght})" : "") . " " . (($f->pk === "true") ? $this->db->get_pk_auto() : "");
                    $this->db->query($sql);
                }
            }
            $this->on_alter();
        }
    }

    public function jsonSerialize() {
        unset($this->fields);
        unset($this->table_name);
        unset($this->declared_fields);
        unset($this->db);
        return $this;
    }

}
