<?php

/**
 * 
 * @return \SQL
 */
function sql ( $db = null ) {
    return new SQL ( $db );
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
    public $orderby = [];
    public $join = [];
    public $limit = -1;
    public $offset = -1;
    public $sql = '';
    public $alias = null;
    public $class = null;

    function implode_values ( $array ) {
        return implode ( "," , array_map ( function($v) {
                    return is_string ( $v ) ? $v : (($v === null) ? 'null' : $v);
                } , $array ) );
    }

    private function is_multibyte ( $s ) {
        if ( $s === '' ) {
            return false;
        }
        if ( is_numeric ( $s ) ) {
            return false;
        }
        $finfo = new finfo ( FILEINFO_MIME_ENCODING );
        return $finfo->buffer ( $s ) === 'binary';
    }

    public function __construct ( $db = null ) {
        if ( $db === null ) {
            $this->db = db ();
        } else {
            $this->db = $db;
        }
        $this->escape = $this->db->escape;
    }

    /**
     * 
     * @param type $fields key = name, value = type
     */
    function create ( $fields ) {
        if ( $this->db->table_exists ( $this->table ) ) {
            $t_f = $this->db->table_fields ( $this->table );
            foreach ( $fields as $key => $value ) {
                if ( ! in_array ( $key , col ( $t_f , 'name' ) ) ) {
                    $sql = 'alter table ' . $this->table . ' add column ' . $key . ' ' . $value;
                    return $this->db->query ( $sql );
                }
            }
        } else {
            $sql = 'create table ' . $this->table . ' ';
            if ( ! key_exists ( 'id' , $fields ) ) {
                if ( $this->db->driver === 'sqlite' ) {
                    $fields['id'] = 'integer primary key autoincrement';
                } elseif ( $this->db->driver === 'pgsql' ) {
                    $fields['id'] = 'serial primary key';
                } else {
                    $fields['id'] = 'int auto_increment primary key';
                }
            }
            $sql .= '(' . implode ( ',' , array_map ( function($key , $value) {
                                return $key . ' ' . $value;
                            } , array_keys ( $fields ) , $fields ) ) . ')';
            return $this->db->query ( $sql );
        }
    }

    function drop () {
        return $this->db->query ( 'drop table ' . $this->table );
    }

    /**
     * Set the table name
     * @param string $name
     * @param Db $db
     * @return $this
     */
    function table ( $name ) {
        $this->table = $name;
        $this->alias = $name;
        return $this;
    }

    /**
     * 
     * @param int $value
     */
    function limit ( $value ) {
        $this->limit = $value;
        return $this;
    }

    function group ( $field ) {
        $this->group[] = $field;
        return $this;
    }

    function having ( $hav ) {
        $this->having[] = $hav;
        return $this;
    }

    function orderby ( $field , $mode = 'DESC' ) {
        $this->orderby[] = [$field , $mode];
        return $this;
    }

    function join ( $table , $tableField , $id = 'id' , $class = null , $count = 'many' ) {
        $this->join[] = [$table , $tableField , $id , $class , $count];
        return $this;
    }

    function where ( $field , $operator = null , $value = null , $cond = null ) {
        if ( is_array ( $field ) ) {
            foreach ( $field as $key => $value ) {
                if ( ! is_array ( $value ) ) {
                    $this->where ( $key , $value );
                } else {
                    if ( count ( $value ) === 2 ) {
                        $this->where ( $key , $value );
                    } else if ( count ( $value ) === 3 ) {
                        $this->where ( $value[0] , $value[1] , $value[2] );
                    } else if ( count ( $value ) === 4 ) {
                        $this->where ( $value[0] , $value[1] , $value[2] , $value[3] );
                    } else {
                        //?
                        $this->where ( $key , $value );
                    }
                }
            }
            return $this;
        }
        if ( $operator === null ) {
            $this->where[] = [$field];
        } else if ( $value === null && substr ( trim ( $operator ) , 0 , 2 ) !== 'is' ) {
            if ( $operator instanceof ArrayAccess ) {
                $this->where[] = [$field , 'in' , $operator];
            } else {
                $this->where[] = [$field , $operator];
            }
        } else if ( $cond === null && substr ( trim ( $operator ) , 0 , 2 ) === 'is' ) {
            $this->where[] = [$field , $operator , $value];
        } else {
            $this->where[] = [$field , $operator , $value , $cond];
        }
        return $this;
    }

    private function get_where () {
        $wheres = [];
        $where = $this->where;
        $count_where = count ( $where );

        if ( $count_where > 0 ) {

            $c = 0;
            foreach ( $where as $key => $value ) {
                $c ++;
                $field = $value[0];
                $operator = '=';
                $result = null;
                $cond = 'AND';
                if ( count ( $value ) > 3 ) {
                    $operator = $value[1];
                    $result = $value[2];
                    $cond = len ( $value[3] ) === 0 ? $cond : $value[3];
                } elseif ( count ( $value ) === 3 ) {
                    $operator = $value[1];
                    $result = $value[2];
                } elseif ( count ( $value ) === 2 ) {
                    $result = $value[1];
                } else {
                    // do nothing
                }

                if ( is_array ( $result ) ) {
                    $result = array_map ( function ($v) {
                        return $this->prepare_value_where ( $v );
                    } , $result );
                } else {
                    $result = $this->prepare_value_where ( $result );
                }

                if ( $operator === 'between' ) {
                    $result = $result[0] . ' AND ' . $result[1];
                } elseif ( $operator === 'in' ) {
                    if ( $value[2] instanceof Tightenco\Collect\Support\Collection ) {
                        $value[2] = $value[2]->all ();
                    }
                    $result = '(' . $this->implode_values ( $value[2] ) . ')';
                }
                if ( $c === $count_where ) {
                    $cond = '';
                }
                if ( is_null ( $result ) && substr ( $operator , 0 , 2 ) !== 'is' ) {
                    $wheres[] = ' ' . $field . ' is null ' . $cond . ' ';
                } else {
                    $wheres[] = ' ' . $field . ' ' . $operator . ' ' . (($result === null) ? 'null' : $result) . ' ' . $cond . ' ';
                }
            }
        } else {
            $wheres[] = '1=1';
        }
        return implode ( '' , $wheres );
    }

    /**
     * 
     * @param array $fields 
     * @return $this
     */
    function select ( $fields = [] ) {
        if ( $fields === [] ) {
            $fields = ['*'];
        }
        $sql = 'SELECT ' . implode ( ',' , $fields ) . ' FROM ' . $this->table;
//        if (count($this->where) > 0) {
        $sql .= ' WHERE ';
//        }
        $sql .= $this->get_where ();
        $this->sql = $sql;
        return $this;
    }

    /**
     * @return object object created
     * @param array $data
     */
    function insert ( $data , $count_commit = -1 ) {
        if ( is_array_numeric ( $data ) ) {
            return $this->insert_batch ( $data , $count_commit );
        }
        if ( is_object ( $data ) ) {
            $data = ( array ) $data;
        }
        $prepared = [];
        foreach ( $data as $key => $value ) {
            $prepared[$key] = $this->prepare_value_data ( $value );
        }
        $sql = 'INSERT INTO ' . $this->table . ' (' . implode ( ',' , array_keys ( $prepared ) ) . ' ) VALUES (' . $this->implode_values ( array_values ( $prepared ) ) . ')';
        if ( $this->db->query ( $sql ) === false ) {
            return false;
        } else {
            if ( $count_commit === false ) {
                return true;
            } else {
                //                $this->where($data);
//                return one($this->db->query('SELECT * FROM ' . $this->table . ' WHERE ' . $this->get_where() . ' order by id desc'));
                if ( $this->db->driver === 'sqlserver' ) {
                    //order by id offset 1 rows fetch next 1 rows only
                    return one ( $this->db->query ( 'SELECT * FROM ' . $this->table . '  order by id offset 1 rows fetch next 1 rows only' ) );
                } else {
                    return one ( $this->db->query ( 'SELECT * FROM ' . $this->table . ' order by id desc limit 1' ) );
                }
            }
        }
    }

    private function insert_batch ( $data , $count_commit = -1 ) {
        $this->db->begin_transaction ();
        $c = 0;
        $return = [];
        foreach ( $data as $d ) {
            $tmp = $this->insert ( $d , false );
            if ( $tmp === false ) {
                $this->db->rollback_transaction ();
                return false;
            }
            $return[] = $tmp;
            if ( $count_commit > -1 && $c >= $count_commit ) {
                $this->db->commit_transaction ();
                $this->db->begin_transaction ();
                $c = 0;
            }
            $c ++;
        }
        $this->db->commit_transaction ();
        return $return;
    }

    function update ( $data = [] ) {
        if ( is_object ( $data ) ) {
            $data = ( array ) $data;
        }
        $prepared = [];
        foreach ( $data as $key => $value ) {
            $prepared[$key] = $this->prepare_value_data ( $value );
        }
        $sql = 'update ' . $this->table . ' set ';
        $p = [];
        foreach ( $prepared as $key => $value ) {
            if ( $key === 'id' ) {
                $this->where ( 'id' , $value );
                continue;
            }
            $value = (is_null ( $value )) ? 'null' : $value;
            $p[] = " {$key} = {$value} ";
        }
        $sql .= implode ( ',' , $p );
        if ( count ( $this->where ) > 0 ) {
            $sql .= ' where ' . $this->get_where ();
        }
        return ($this->db->query ( $sql ) === false) ? false : true;
    }

    function delete () {
        $sql = 'delete from ' . $this->table;
        if ( count ( $this->where ) > 0 ) {
            $sql .= ' where ' . $this->get_where ();
        } else {
            if ( ! empty ( $this->id ) ) {
                $this->where ( 'id' , $this->id );
                $sql .= ' where ' . $this->get_where ();
            }
        }
        return ($this->db->query ( $sql ) === false) ? false : true;
    }

    function insert_or_update ( $data ) {
        if ( $this->start () === null ) {
            return $this->insert ( $data );
        } else {
            return $this->update ();
        }
    }

    /**
     * 
     * @return array|Tightenco\Collect\Support\Collection|static
     */
    function get ( $class = null ) {
        if ( $this->sql === '' ) {
            $this->select ();
        }
        if ( $this->class !== null && $class == null ) {
            $class = $this->class;
        }
        if ( count ( $this->group ) > 0 ) {
            $this->sql .= ' GROUP BY ';
            foreach ( $this->group as $g ) {
                $this->sql .= $g . ' ';
            }
        }
        $this->sql .= ' ';
        if ( count ( $this->having ) > 0 ) {
            $this->sql .= ' HAVING ';
            foreach ( $this->having as $h ) {
                $this->sql .= $h . ' ';
            }
        }
        $this->sql .= ' ';
        if ( count ( $this->orderby ) > 0 ) {
            $this->sql .= ' ORDER BY ';
            foreach ( $this->orderby as $o ) {
                $this->sql .= $o[0] . ' ' . $o[1] . ' ';
            }
        }
        $this->sql .= ' ' . (($this->limit > 0) ? ' limit ' . $this->limit : '');
        $data = $this->db->query ( $this->sql , [] , $class );
        if ( count ( $this->join ) > 0 ) {

            for ( $index = 0; $index < count ( $data ); $index ++ ) {
                foreach ( $this->join as $j ) {
                    if ( $j[0] instanceof SQL ) {
                        $j[0] = clone $j[0];
                        if ( $j[4] === 'one' ) {
                            $data[$index]->{$j[0]->alias} = one ( $j[0]->where ( $j[1] , $data[$index]->{$j[2]} )->select ()->get ( $j[3] ) );
                        } else {
                            $data[$index]->{$j[0]->alias} = $j[0]->where ( $j[1] , $data[$index]->{$j[2]} )->select ()->get ( $j[3] );
                        }
                    } else {
                        if ( $j[4] === 'one' ) {
                            $data[$index]->{$j[0]} = one ( sql ( $this->db )->table ( $j[0] )->where ( $j[1] , $data[$index]->{$j[2]} )->get () );
                        } else {
                            $data[$index]->{$j[0]} = sql ( $this->db )->table ( $j[0] )->where ( $j[1] , $data[$index]->{$j[2]} )->get ();
                        }
                    }
                }
            }
        }
        if ( $data === false ) {
            return false;
        }
        return collect ( $data );
    }

    /**
     * 
     * @return object
     */
    function start () {
        return $this->first ( $this->get () );
    }

    /**
     * 
     * @return object
     */
    function first () {
        return $this->get ()->first ();
    }

    /**
     * 
     * @return object
     */
    function end () {
        $d = $this->get ()->last ();
        return $d;
    }

    /**
     * 
     * @return object
     */
    function last () {
        return $this->end ();
    }

    public function prepare_value_data ( $value ) {
        if ( is_null ( $value ) ) {
            return null;
        }
        $is_date = false;
        try {
            Carbon\Carbon::createFromFormat ( conf::$dateFormat , explode ( ' ' , $value )[0] );
            $is_date = true;
        } catch ( Exception $exc ) {
            $is_date = false;
        } finally {
            
        }
        if ( $this->is_multibyte ( $value ) && ! $is_date ) {
            if ( $this->db->driver === 'sqlite' ) {
                return 'x\'' . bin2hex ( $value ) . '\'';
            } else {
                return $this->db->pdo->quote ( $value , PDO::PARAM_LOB );
            }
        } else if ( is_numeric ( $value ) ) {
            return format_number ( $value );
        } else if ( $is_date ) {
            return $this->db->pdo->quote ( cdate ( $value )->format ( 'Y-m-d H:i:s' ) , PDO::PARAM_STR );
        } else {
            return $this->db->pdo->quote ( $value , PDO::PARAM_STR );
        }
    }

    private function prepare_value_where ( $value ) {
        if ( is_null ( $value ) ) {
            return null;
        }
        $is_date = false;
        try {
            Carbon\Carbon::createFromFormat ( conf::$dateFormat , explode ( ' ' , $value )[0] );
            $is_date = true;
        } catch ( Exception $exc ) {
            $is_date = false;
        } finally {
            
        }

        if ( is_string ( $value ) && ! $is_date ) {
            return $this->db->pdo->quote ( $value , PDO::PARAM_STR );
        } else if ( is_numeric ( $value ) && $value[0] !== '0' ) {
            return format_number ( $value );
        } else if ( $is_date ) {
            return $this->db->pdo->quote ( cdate ( $value )->format ( 'Y-m-d H:i:s' ) , PDO::PARAM_STR );
        } else {
            return $this->db->pdo->quote ( $value , PDO::PARAM_STR );
        }
    }

}
