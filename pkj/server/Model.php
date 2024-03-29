<?php

class Model implements arrayaccess {

    function seed () {
        return false;
    }

    function relationOne ( $table , $field , $id = 'id' ) {
        return $this->relation ( $table , $field , $id , 'one' );
    }

    function relationMany ( $table , $field , $id = 'id' ) {
        return $this->relation ( $table , $field , $id , 'many' );
    }

    /**
     * 
     * @param type $table model name from table
     * @param type $field 
     * @param type $id
     * @param type $count
     * @return type
     */
    function relation ( $table , $field , $id = 'id' , $count = 'many' ) {
        if ( is_string ( $table ) ) {
            $table = db ()->table ( $table );
        }
        if ( $count === 'one' ) {
            return $table->where ( $id , $this->raw ( $field ) )->first ();
        } else {
            return $table->where ( $id , $this->raw ( $field ) )->get ();
        }
    }

    //eventos
    function after_insert () {
        
    }

    function before_insert ( &$return ) {
        
    }

    function after_update ( $where ) {
        
    }

    function before_update ( $where , &$return ) {
        
    }

    function after_delete ( $where ) {
        
    }

    function before_delete ( $where , &$return ) {
        
    }

    function on_error ( $error ) {
        
    }

    /**
     * 
     * @param type $event insert,update,delete,create,drop,error
     * @param type $args
     */
    function on_event ( $event , $args = [] ) {
        
    }

    function get_default ( $field ) {
        s ( 'Não implementado' );
    }

    function on_set ( &$field , &$value ) {
        
    }

    function on_get ( &$field , &$value ) {
        
    }

    private $doc;

    /**
     *
     * @var SQL  
     */
    private $sql = null;
    private $data = [];

    public function __construct () {
        $this->sql = new SQL ( db () );
        $this->sql->join = [];
        $this->sql->class = get_class ( $this );
        $class = new ReflectionClass ( $this->sql->class );
        $comment = $class->getDocComment ();
        $this->__parse_doc ( $comment );
        $this->sql->table = $this->doc['table'];
        $autoload = isset ( $this->doc['autoload'] ) ? $this->doc['autoload'] : [];
        if ( $autoload === null ) {
            $autoload = [];
        }
        foreach ( $this->doc['property'] as $p ) {
            if ( ! in_array ( $p[1] , $autoload ) ) {
                continue;
            }
            $fk = explode ( '=' , $p[3] );
            $exfk = explode ( ' ' , $fk[1] );
            $count = 'many';
            if ( count ( $exfk ) > 1 ) {
                $fk[1] = $exfk[0];
                $count = $exfk[1];
            }
            $this->load ( $p[1] , $fk[0] , $fk[1] , substr ( $p[2] , 1 ) , $count );
            $obj = new ReflectionClass ( $p[1] );
            if ( $count === 'one' ) {
                $this->{substr ( $p[2] , 1 )} = $obj->newInstance ();
            } else {
                $this->{substr ( $p[2] , 1 )} = [];
            }
        }
    }

    /**
     * 
     * @return \SQL
     */
    function sql () {
        return $this->sql;
    }

    function error () {
        $this->on_event ( 'error' , ['error' => $this->sql->db->last_error] );
        return $this->sql->db->last_error;
    }

    public function __set ( $name , $value ) {
        $info = $this->doc['property'][$name];
//        if (is_array($value)) {
//            return;
//        }
        $this->on_set ( $name , $value );
        $this->data[$name] = $value;
    }

    public function __toString () {
        return isset ( $this->data['id'] ) ? strval ( $this->data['id'] ) : '';
    }

    function doc () {
        return $this->doc;
    }

    public function raw ( $key = null ) {
        if ( $key === null ) {
            return $this->data;
        } else {
            if ( array_key_exists ( $key , $this->data ) ) {
                return $this->data[$key];
            } else {
                return null;
            }
        }
    }

    public function __get ( $name ) {
        $return = null;
        if ( isset ( $this->data[$name] ) ) {
            $return = $this->translate_get ( $name );
        } else {
            $return = null;
        }
        $this->on_get ( $name , $return );
        return $return;
    }

    private function translate_get ( $name ) {
        $info = $this->doc['property'][$name];
        $value = $this->data[$name];
        $tipo = $this->sql->db->translate_field ( $info[1] );
        switch ( $tipo ) {
            case 'integer':
                return cint ( $value );
                break;
            case 'float':
                return cdouble ( $value );
                break;
            case 'string':
                return trim ( $value );
                break;
            case 'datetime':
            case 'date':
                return cdate ( $value );
                break;
            case 'blob':
                if ( $info[1] === 'image' ) {
                    return 'data://image;base64,' . base64_encode ( $value );
                } else {
                    return $value;
                }
                break;
            default:
                return $value;
        }
    }

    public function fromArray ( $array ) {
        $coluns = array_values ( array_map ( function($value) {
                    return substr ( $value[2] , 1 );
                } , $this->doc['property'] ) );
        foreach ( $array as $key => $value ) {
            if ( in_array ( $key , $coluns ) ) {
//                if ($array[$key] !== null && $array[$key] !== '') {
                $this->{$key} = $value;
//                }
            }
        }
        return $this;
    }

    public function setValues () {
        $coluns = array_values ( array_map ( function($value) {
                    return substr ( $value[2] , 1 );
                } , $this->doc['property'] ) );
        foreach ( $coluns as $c ) {
            setValue ( 'name="' . $c . '"' , $this->{$c} );
        }
    }

    /**
     * @todo Precisa criar reversão de muitos para 1 
     * @param string $class
     * @param string $alias
     */
    function load ( $class , $me , $fk , $alias = null , $count = 'many' ) {
        if ( class_exists ( $class ) && ! in_array ( $class , ['datetime'] ) ) {
            $objClass = (new ReflectionClass ( $class ) )->newInstance ();
            $objSQL = new SQL ( db () );
            $objSQL->class = $class;
            $objSQL->table = $objClass->doc['table'];
            $objSQL->alias = $alias;
            if ( $objSQL->alias === null ) {
                $objSQL->alias = $objSQL->table;
            }
            $this->sql->join[] = [$objSQL , $fk , $me , $class , $count];
        }
    }

    function select ( $fields = [] ) {
        return $this->sql->select ( $fields );
    }

    function where ( $field , $operator = null , $value = null , $cond = null ) {
        $this->sql->where ( $field , $operator , $value , $cond );
        return $this;
    }

    /**
     * 
     * @param integer $id
     * @return static
     */
    function byId ( $id , $default = [] ) {
        if ( strlen ( $id ) === 0 ) {
            return $this->fromArray ( $default );
        }
        return $this->where ( [
                    'id' => $id
                ] )->first ();
    }

    function orderby ( $field , $mode = 'DESC' ) {
        $this->sql->orderby ( $field , $mode );
        return $this;
    }

    function group ( $field ) {
        $this->sql->group ( $field );
        return $this;
    }

    function having ( $hav ) {
        $this->sql->having ( $hav );
        return $this;
    }

    /**
     * 
     * @return static|array
     */
    function get () {
        return $this->sql->get ( get_class ( $this ) );
    }

    function col ( $name ) {
        return $this->column ( $name );
    }

    function column ( $name ) {
        return $this->get ()->map ( function ($l) use($name) {
                    return $l->{$name};
                } );
    }

    /**
     * 
     * @return static
     */
    function first () {
        $this->sql->class = get_class ( $this );
        return $this->sql->first ();
    }

    /**
     * 
     * @return static
     */
    function last () {
        $this->sql->class = get_class ( $this );
        return $this->sql->last ();
    }

    function insert ( $count_limit = -1 ) {
        if ( is_array ( $count_limit ) ) {
            $this->data += $count_limit;
            $count_limit = -1;
        }
        $this->on_event ( 'insert' , ['count_limit' => $count_limit] );
        if ( $this->after_insert () === false ) {
            return false;
        }
        $return = null;
        $insertData = [];
        foreach ( $this->data as $k => $d ) {
            if ( is_array ( $d ) ) { // para elementos com autoload
                continue;
            }
            if ( $d instanceof Model ) {
                if ( $d->id !== null ) {
                    $insertData[$k] = $d->id;
                }
            } else {
                $insertData[$k] = $d;
            }
        }
        $return = $this->sql->insert ( $insertData , $count_limit );
        if ( $return === false ) {
            $this->on_error ( $this->error () );
        }
        $this->before_insert ( $return );
        return $return;
    }

    function update () {
        $this->on_event ( 'update' );
        if ( $this->after_update ( $this->sql->where ) === false ) {
            return false;
        }
        $return = null;
        $updateData = [];
        foreach ( $this->data as $k => $d ) {
            if ( is_array ( $d ) ) { // para elementos com autoload
                continue;
            }
            if ( $d instanceof Model ) {
                if ( $d->id !== null ) {
                    $updateData[$k] = $d->id;
                }
            } else {
                $updateData[$k] = $d;
            }
        }
        $return = $this->sql->update ( $updateData );
        if ( $return === false ) {
            $this->on_error ( $this->error () );
        }
        $this->after_update ( $this->sql->where , $return );
        return $return;
    }

    function insert_or_update () {
        $this->on_event ( 'insert_or_update' );
        $return = $this->sql->insert_or_update ( $this->data );
        if ( $return === false ) {
            $this->on_error ( $this->error () );
        }
        return $return;
    }

    function delete () {
        $this->on_event ( 'delete' );
        if ( $this->after_delete ( $this->sql->where ) === false ) {
            return false;
        }
        $return = null;
        if ( $this->id !== null && count ( $this->sql->where ) < 1 ) {
            $this->where ( 'id' , $this->id );
        }
        $return = $this->sql->delete ();
        if ( $return === false ) {
            $this->on_error ( $this->error () );
        }
        $this->before_delete ( $this->sql->where , $return );
        return $return;
    }

    function drop () {
        $this->on_event ( 'drop' );
        return $this->sql->drop ();
    }

    function create () {
        $this->on_event ( 'create' );
        $data = [];
        foreach ( $this->doc['property'] as $key => $value ) {
            if ( $key === 'id' ) {
                continue;
            }
//            if (class_exists($value[1]) && !in_array($value[1], ['datetime'])) {
//                continue;
//            }

            $data[$key] = $this->sql->db->detranslate_field ( $value[1] );
        }
        $r = $this->sql->create ( $data );
        $seed = $this->seed ();
        if ( $seed !== false && $this->get ()->count () < 1 ) {
            db ()->begin_transaction ();
            foreach ( $seed as $value ) {
                $this->fromArray ( $value )->insert ();
            }
            db ()->commit_transaction ();
        }
        return $r;
    }

    private function __parse_doc ( $comment ) {
        $docs = [];
        foreach ( explode ( "\n" , $comment ) as $line ) {
            $line = trim ( $line );
            if ( ! in_array ( $line , ['' , '/**' , '*/'] ) ) {
                $value = [];
                $tmp = array_map ( function ($v) {
                    return ($v !== ' ') ? $v : '';
                } , explode ( ' ' , $line ) );
                $tmp = array_slice ( $tmp , 1 );

                if ( $tmp[0] === '@table' ) {
                    $doc['table'] = $tmp[1];
                } elseif ( $tmp[0] === '@alias' ) {
                    $doc['alias'] = $tmp[1];
                } elseif ( $tmp[0] === '@autoload' ) {
                    $doc['autoload'] = explode ( ',' , $tmp[1] );
                } else {
                    if ( count ( $tmp ) > 1 ) {
                        $tmp[3] = implode ( ' ' , array_slice ( $tmp , 3 ) );
                        for ( $index = 4; $index <= count ( $tmp ); $index ++ ) {
                            unset ( $tmp[$index] );
                        }
                    }
                    $doc['property'][substr ( $tmp[2] , 1 )] = $tmp;
                }
            }
        }
        $this->doc = $doc;
    }

    public function toArray () {
        $data = [];
        foreach ( $this->doc['property'] as $key => $value ) {
            $data[$key] = $this->{$key} . '';
        }
        return $data;
    }

    public function toJson () {
        $json = [];
        foreach ( $this->doc['property'] as $key => $value ) {
            $valor = $this->{$key};
            $json[] = '"' . $key . '":"' . $valor . '"';
        }
        return '{' . implode ( ',' , $json ) . '}';
    }

    public function offsetExists ( $offset ): bool {
        return isset ( $this->data[$offset] );
    }

    public function offsetGet ( $offset ) {
        return $this->{$offset};
    }

    public function offsetSet ( $offset , $value ): void {
        $this->{$offset} = $value;
    }

    public function offsetUnset ( $offset ): void {
        $this->{$offset} = null;
    }

}
