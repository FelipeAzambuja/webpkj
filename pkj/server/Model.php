<?php

class Model {

    //eventos
    function after_insert() {
        
    }

    function before_insert(&$return) {
        
    }

    function after_update($where) {
        
    }

    function before_update($where, &$return) {
        
    }

    function after_delete($where) {
        
    }

    function before_delete($where, &$return) {
        
    }

    function on_error($error) {
        
    }

    /**
     * 
     * @param type $event insert,update,delete,create,drop,error
     * @param type $args
     */
    function on_event($event, $args = []) {
        
    }

    function get_default($field) {
        s('Não implementado');
    }

    function on_set(&$field, &$value) {
        
    }

    function on_get(&$field, &$value) {
        
    }

    private $doc;

    /**
     * SQL
     *
     * @var \SQL
     */
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
            $exfk = explode(' ', $fk[1]);
            $count = 'many';
            if (count($exfk) > 1) {
                $fk[1] = $exfk[0];
                $count = $exfk[1];
            }
            $this->load($p[1], $fk[0], $fk[1], substr($p[2], 1), $count);
        }
    }

    function error() {
        $this->on_event('error', ['error' => $this->sql->db->last_error]);
        return $this->sql->db->last_error;
    }

    public function __set($name, $value) {
        $info = $this->doc['property'][$name];
//        if (is_array($value)) {
//            return;
//        }
        $this->on_set($name, $value);
        $this->data[$name] = $value;
    }

    public function __get($name) {
        $return = null;
        if (isset($this->data[$name])) {
            $return = $this->translate_get($name);
        } else {
            $return = null;
        }
        $this->on_get($name, $return);
        return $return;
    }

    private function translate_get($name) {
        $info = $this->doc['property'][$name];
        $value = $this->data[$name];
        $tipo = $this->sql->db->translate_field($info[1]);
        switch ($tipo) {
            case 'integer':
                return cint($value);
                break;
            case 'float':
                return cfloat($value);
                break;
            case 'string':
                return trim($value);
                break;
            case 'date':
                return cdate($value);
                break;
            case 'blob':
                if ($info[1] === 'image') {
                    return 'data://image;base64,' . base64_encode($value);
                } else {
                    return $value;
                }
                break;
            default:
                return $value;
        }
    }

    public function fromArray($array) {
        $coluns = array_values(array_map(function($value) {
                    return substr($value[2], 1);
                }, $this->doc['property']));
        foreach ($array as $key => $value) {
            if (in_array($key, $coluns)) {
                if ($array[$key] !== null && $array[$key] !== '') {
                    $this->{$key} = $array[$key];
                }
            }
        }
        return $this;
    }

    public function setValues() {
        $coluns = array_values(array_map(function($value) {
                    return substr($value[2], 1);
                }, $this->doc['property']));
        foreach ($coluns as $c) {
            setValue('name="' . $c . '"', $this->{$c});
        }
    }

    /**
     * @todo Precisa criar reversão de muitos para 1 
     * @param string $class
     * @param string $alias
     */
    function load($class, $me, $fk, $alias = null, $count = 'many') {
        if (class_exists($class) && !in_array($class, ['datetime'])) {
            $objClass = (new ReflectionClass($class))->newInstance();
            $objSQL = new SQL(db());
            $objSQL->class = $class;
            $objSQL->table = $objClass->doc['table'];
            $objSQL->alias = $alias;
            if ($objSQL->alias === null) {
                $objSQL->alias = $objSQL->table;
            }
            $this->sql->join[] = [$objSQL, $fk, $me, $class, $count];
        }
    }

    function select($fields = []) {
        return $this->sql->select($fields);
    }

    function where($field, $operator = null, $value = null, $cond = null) {
        $this->sql->where($field, $operator = null, $value = null, $cond = null);
        return $this;
    }

    /**
     * 
     * @return static|array
     */
    function get() {
        return $this->sql->get(get_class($this));
    }

    function insert($count_limit = -1) {
        $this->on_event('insert', ['count_limit' => $count_limit]);
        if ($this->after_insert() === false) {
            return false;
        }
        $return = null;
        $return = $this->sql->insert($this->data, $count_limit);
        if ($return === false) {
            $this->on_error($this->error());
        }
        $this->before_insert($return);
        return $return;
    }

    function update() {
        $this->on_event('update');
        if ($this->after_update($this->sql->where) === false) {
            return false;
        }
        $return = null;
        $return = $this->sql->update($this->data);
        if ($return === false) {
            $this->on_error($this->error());
        }
        $this->after_update($this->sql->where, $return);
        return $return;
    }

    function insert_or_update() {
        //implementar evento pode causar perda de desempenho
        $this->on_event('insert_or_update');
        $return = $this->sql->insert_or_update($this->data);
        if ($return === false) {
            $this->on_error($this->error());
        }
        return $return;
    }

    function delete() {
        $this->on_event('delete');
        if ($this->after_delete($this->sql->where) === false) {
            return false;
        }
        $return = null;
        $return = $this->sql->delete();
        if ($return === false) {
            $this->on_error($this->error());
        }
        $this->before_delete($this->sql->where, $return);
        return $return;
    }

    function drop() {
        $this->on_event('drop');
        return $this->sql->drop();
    }

    function create() {
        $this->on_event('create');
        $data = [];
        foreach ($this->doc['property'] as $key => $value) {
            if ($key === 'id') {
                continue;
            }
//            if (class_exists($value[1]) && !in_array($value[1], ['datetime'])) {
//                continue;
//            }

            $data[$key] = $this->sql->db->detranslate_field($value[1]);
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
