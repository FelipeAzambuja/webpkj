<?php

class Model {
    private $doc;
    private $sql = null;
    private $data = [];

    public function __construct() {
        $this->sql = new SQL(db());
        $this->sql->join = [];
        $this->sql->class = get_class($this);
        $class = new ReflectionClass($this->sql->class);
        $comment = $class->getDocComment();
        $this->__parse_doc($comment);
        $this->sql->table = $this->doc['table'];
        $autoload = isset($this->doc['autoload']) ? $this->doc['autoload'] : [];
        if ($autoload === null) {
            $autoload = [];
        }
        foreach ($this->doc['property'] as $p) {
            if (!in_array($p[1], $autoload)) {
                continue;
            }
            $fk = explode('=', $p[3]);
            $this->load($p[1], $fk[0], $fk[1], substr($p[2], 1));
        }
    }

    public function __set($name, $value) {
        $info = $this->doc['property'][$name];
        if(is_array($value)){
            return;
        }
        switch ($info[1]) {
            case 'integer':
            case 'int':
                $this->data[$name] = cint($value);
                break;
            default:
                $this->data[$name] = trim($value);
                break;
        }
        $this->{$name} = $this->data[$name];
    }

    /**
     * @todo Precisa criar reversão de muitos para 1 
     * @param string $class
     * @param string $alias
     */
    function load($class, $me, $fk, $alias = null) {
        if (class_exists($class) && !in_array($class, ['datetime'])) {
            $objClass = (new ReflectionClass($class))->newInstance();
            $objSQL = new SQL(db());
            $objSQL->class = $class;
            $objSQL->table = $objClass->doc['table'];
            $objSQL->alias = $alias;
            if ($objSQL->alias === null) {
                $objSQL->alias = $objSQL->table;
            }
            $this->sql->join[] = [$objSQL, $fk, $me, $class];
        }
    }

    function select($fields = []) {
        return $this->sql->select($fields);
    }
    /**
     * Add return 
     *
     * @param [type] $field
     * @param [type] $operator
     * @param [type] $value
     * @param [type] $cond
     * @return static|Model
     */
    function where($field, $operator = null, $value = null, $cond = null) {
        $this->sql->where($field, $operator = null, $value = null, $cond = null);
        return $this;
    }

    /**
     * 
     * @return static
     */
    function get() {
        return $this->sql->get();
    }

    function insert($count_limit = -1) {
        return $this->sql->insert($this->data, $count_limit);
    }

    function update() {
        return $this->sql->update($this->data);
    }

    function insert_or_update() {
        return $this->sql->insert_or_update($this->data);
    }

    function delete() {
        return $this->sql->delete();
    }

    function drop() {
        return $this->sql->drop();
    }

    function create() {
        $data = [];
        foreach ($this->doc['property'] as $key => $value) {
            if ($key === 'id') {
                continue;
            }
            if (class_exists($value[1]) && !in_array($value[1], ['datetime'])) {
                continue;
            }
            $data[$key] = $value[1];
        }
        return $this->sql->create($data);
    }

    private function __parse_doc($comment) {
        $docs = [];
        foreach (explode("\n", $comment) as $line) {
            $line = trim($line);
            if (!in_array($line, ['', '/**', '*/'])) {
                $value = [];
                $tmp = array_map(function ($v) {
                    return ($v !== ' ') ? $v : '';
                }, explode(' ', $line));
                $tmp = array_slice($tmp, 1);

                if ($tmp[0] === '@table') {
                    $doc['table'] = $tmp[1];
                } elseif ($tmp[0] === '@alias') {
                    $doc['alias'] = $tmp[1];
                } elseif ($tmp[0] === '@autoload') {
                    $doc['autoload'] = explode(',', $tmp[1]);
                } else {
                    if (count($tmp) > 1) {
                        $tmp[3] = implode(' ', array_slice($tmp, 3));
                        for ($index = 4; $index <= count($tmp); $index++) {
                            unset($tmp[$index]);
                        }
                    }
                    $doc['property'][substr($tmp[2], 1)] = $tmp;
                }
            }
        }
        $this->doc = $doc;
    }

}
