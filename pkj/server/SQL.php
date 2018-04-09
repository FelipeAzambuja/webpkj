<?php

/**
 * 
 * @return \SQL
 */
function sql($db = null) {
    return new SQL($db);
}

/**
 * @todo implementar group by e order by 
 * @property Db $db DB
 */
class SQL {

    /**
     *
     * @var Db 
     */
    public $db = null;
    public $escape = null;
    public $table = null;
    public $where = [];
    public $data = [];
    public $group = [];
    public $having = [];
    public $join = [];
    public $limit = -1;
    public $offset = -1;
    public $sql = '';
    public $alias = null;
    public $class = null;

    private function is_multibyte($s) {
        if ($s === '') {
            return false;
        }
        if (is_numeric($s)) {
            return false;
        }
        $finfo = new finfo(FILEINFO_MIME_ENCODING);
        return $finfo->buffer($s) === 'binary';
    }

    public function __construct($db = null) {
        if ($db === null) {
            $this->db = db();
        } else {
            $this->db = $db;
        }
        $this->escape = $this->db->escape;
    }

    /**
     * 
     * @param type $fields key = name, value = type
     */
    function create($fields) {
        if ($this->db->table_exists($this->table)) {
            $t_f = $this->db->table_fields($this->table);
            foreach ($fields as $key => $value) {
                if (!in_array($key, col($t_f, 'name'))) {
                    $sql = 'alter table ' . $this->table . ' add column ' . $key . ' ' . $value;
                    return $this->db->query($sql);
                }
            }
        } else {
            $sql = 'create table ' . $this->table . ' ';
            if (!key_exists('id', $fields)) {
                if ($this->db->driver === 'sqlite') {
                    $fields['id'] = 'integer primary key autoincrement';
                } elseif ($this->db->driver === 'pgsql') {
                    $fields['id'] = 'serial primary key';
                } else {
                    $fields['id'] = 'int auto_increment primary key';
                }
            }
            $sql .= '(' . implode(',', array_map(function($key, $value) {
                                return $key . ' ' . $value;
                            }, array_keys($fields), $fields)) . ')';
            return $this->db->query($sql);
        }
    }

    function drop() {
        return $this->db->query('drop table ' . $this->table);
    }

    /**
     * Set the table name
     * @param string $name
     * @param Db $db
     * @return $this
     */
    function table($name) {
        $this->table = $name;
        $this->alias = $name;
        return $this;
    }

    function group($field) {
        $this->group[] = $field;
    }

    function having($hav) {
        $this->group[] = $hav;
    }

    function join($table, $tableField, $id = 'id', $class = null, $count = 'many') {
        $this->join[] = [$table, $tableField, $id, $class, $count];
        return $this;
    }

    function where($field, $operator = null, $value = null, $cond = null) {
        if (is_array($field)) {
            foreach ($field as $key => $value) {
                $this->where($key, $value);
            }
            return $this;
        }
        if ($operator === null) {
            $this->where[] = [$field];
        } else if ($value === null) {
            $this->where[] = [$field, $operator];
        } else if ($cond === null) {
            $this->where[] = [$field, $operator, $value];
        } else {
            $this->where[] = [$field, $operator, $value, $cond];
        }
        return $this;
    }

    private function get_where() {
        $wheres = [];
        $count_where = count($this->where);
        if ($count_where > 0) {

            $c = 0;
            foreach ($this->where as $key => $value) {
                $c ++;
                $field = $value[0];
                $operator = '=';
                $result = null;
                $cond = 'AND';
                if (count($value) > 3) {
                    $operator = $value[1];
                    $result = $value[2];
                    $cond = $value[3];
                } elseif (count($value) === 3) {
                    $operator = $value[1];
                    $result = $value[2];
                } elseif (count($value) === 2) {
                    $result = $value[1];
                } else {
                    // do nothing
                }

                if (is_array($result)) {
                    $result = array_map(function ($v) {
                        return $this->prepare_value_where($v);
                    }, $result);
                } else {
                    $result = $this->prepare_value_where($result);
                }

                if ($operator === 'between') {
                    $result = $result[0] . ' AND ' . $result[1];
                } elseif ($operator === 'in') {
                    $result = '(' . implode(',', $result) . ')';
                }
                if ($c === $count_where) {
                    $cond = '';
                }
                if (is_null($result)) {
                    $wheres[] = ' ' . $field . ' is null ' . $cond . ' ';
                } else {
                    $wheres[] = ' ' . $field . ' ' . $operator . ' ' . $result . ' ' . $cond . ' ';
                }
            }
        }
        return implode('', $wheres);
    }

    /**
     * 
     * @param array $fields 
     * @return $this
     */
    function select($fields = []) {
        if ($fields === []) {
            $fields = ['*'];
        }
        $sql = 'SELECT ' . implode(',', $fields) . ' FROM ' . $this->table;
        if (count($this->where) > 0) {
            $sql .= ' WHERE ';
        }
        $sql .= $this->get_where();
        $this->sql = $sql;
        return $this;
    }

    /**
     * @return object object created
     * @param array $data
     */
    function insert($data, $count_commit = -1) {
        if (is_array_numeric($data)) {
            return $this->insert_batch($data, $count_commit);
        }
        if (is_object($data)) {
            $data = (array) $data;
        }
        $prepared = [];
        foreach ($data as $key => $value) {
            $prepared[$key] = $this->prepare_value_data($value);
        }
        $sql = 'INSERT INTO ' . $this->table . ' (' . implode(',', array_keys($prepared)) . ' ) VALUES (' . implode(',', array_values($prepared)) . ')';
        if ($this->db->query($sql) === false) {
            return false;
        } else {
            if ($count_commit === false) {
                return true;
            } else {
                $this->where($data);
                return one($this->db->query('SELECT * FROM ' . $this->table . ' WHERE ' . $this->get_where() . ' order by id desc'));
            }
        }
    }

    private function insert_batch($data, $count_commit = -1) {
        $this->db->begin_transaction();
        $c = 0;
        $return = [];
        foreach ($data as $d) {
            $tmp = $this->insert($d, false);
            if ($tmp === false) {
                $this->db->rollback_transaction();
                return false;
            }
            $return[] = $tmp;
            if ($count_commit > -1 && $c >= $count_commit) {
                $this->db->commit_transaction();
                $this->db->begin_transaction();
                $c = 0;
            }
            $c++;
        }
        $this->db->commit_transaction();
        return $return;
    }

    function update($data = []) {
        if (is_object($data)) {
            $data = (array) $data;
        }
        $prepared = [];
        foreach ($data as $key => $value) {
            $prepared[$key] = $this->prepare_value_data($value);
        }
        $sql = 'update ' . $this->table . ' set ';
        $p = [];
        foreach ($prepared as $key => $value) {
            $p[] = " {$key} = {$value} ";
        }
        $sql .= implode(',', $p);
        if (count($this->where) > 0) {
            $sql .= ' where ' . $this->get_where();
        }
        return $this->db->query($sql) === false;
    }

    function delete() {
        $sql = 'delete from ' . $this->table;
        if (count($this->where) > 0) {
            $sql .= ' where ' . $this->get_where();
        }
        return $this->db->query($sql) === false;
    }

    function insert_or_update($data) {
        if ($this->start() === null) {
            return $this->insert($data);
        } else {
            return $this->update();
        }
    }

    /**
     * 
     * @return array|static
     */
    function get($class = null) {
        if ($this->sql === '') {
            $this->select();
        }
        if ($this->class !== null && $class == null) {
            $class = $this->class;
        }
        $data = $this->db->query($this->sql, [], $class);
        if (count($this->join) > 0) {

            for ($index = 0; $index < count($data); $index++) {
                foreach ($this->join as $j) {
                    if ($j[0] instanceof SQL) {
                        if ($j[4] === 'one') {
                            $data[$index]->{$j[0]->alias} = one($j[0]->where($j[1], $data[$index]->{$j[2]})->select()->get($j[3]));
                        } else {
                            $data[$index]->{$j[0]->alias} = $j[0]->where($j[1], $data[$index]->{$j[2]})->select()->get($j[3]);
                        }
                        
                    } else {
                        if ($j[4] === 'one') {
                            $data[$index]->{$j[0]} = one(sql($this->db)->table($j[0])->where($j[1], $data[$index]->{$j[2]})->get());
                        } else {
                            $data[$index]->{$j[0]} = sql($this->db)->table($j[0])->where($j[1], $data[$index]->{$j[2]})->get();
                        }
                        
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 
     * @return object
     */
    function start() {
        return $this->first($this->get());
    }

    /**
     * 
     * @return object
     */
    function first() {
        return one($this->get());
    }

    /**
     * 
     * @return object
     */
    function end() {
        $d = $this->get();
        return end($d);
    }

    /**
     * 
     * @return object
     */
    function last() {
        return $this->end();
    }

    public function prepare_value_data($value) {
        if (is_null($value)) {
            return null;
        }
        if ($this->is_multibyte($value)) {
            if ($this->db->driver === 'sqlite') {
                return 'x\'' . bin2hex($value) . '\'';
            } else {
                return $this->db->pdo->quote($value, PDO::PARAM_LOB);
            }
        } else if (is_date($value)) {
            return $this->db->pdo->quote(cdate($value)->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        } else {
            return $this->db->pdo->quote($value, PDO::PARAM_STR);
        }
    }

    private function prepare_value_where($value) {
        if (is_null($value)) {
            return null;
        }
        if (is_string($value)) {
            return $this->db->pdo->quote($value, PDO::PARAM_STR);
        } else if (is_date($value)) {
            return $this->db->pdo->quote(cdate($value)->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        } else {
            return $this->db->pdo->quote($value, PDO::PARAM_STR);
        }
    }

}
