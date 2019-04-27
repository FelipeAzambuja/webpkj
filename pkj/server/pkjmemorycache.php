<?php

if ( function_exists ( 'shm_attach' ) ) {

    function memory_cache_put ( $name , $data , $minutes = 20 ) {
        if ( ! mem_has ( 'cache' ) ) {
            $cache = [];
        } else {
            $cache = mem_get ( 'cache' );
        }
        $i = -1;
        foreach ( $cache as $key => $value ) {
            if ( $value['name'] === $name ) {
                $i = $key;
                break;
            }
        }

        if ( $i > -1 ) {
            $cache[$i] = [
                'name' => $name ,
                'create' => (new DateTime ( 'now' , new DateTimeZone ( 'America/Sao_Paulo' ) ) )->format ( 'Y-m-d H:i:s' ) ,
                'minutes' => $minutes ,
                'data' => $data
            ];
        } else {
            $cache[] = [
                'name' => $name ,
                'create' => (new DateTime ( 'now' , new DateTimeZone ( 'America/Sao_Paulo' ) ) )->format ( 'Y-m-d H:i:s' ) ,
                'minutes' => $minutes ,
                'data' => $data
            ];
        }


        mem_put ( 'cache' , $cache );
    }

    function memory_cache_get ( $name ) {
        if ( ! mem_has ( 'cache' ) ) {
            return null;
        }
        $cache_raw = mem_get ( 'cache' );
        $cache = [];
        foreach ( $cache_raw as $i => $value ) {
            if ( $value !== null ) {
                $value['i'] = $i;
                $cache[$value['name']] = $value;
            }
        }
        if ( isset ( $cache[$name] ) ) {
            $value = $cache[$name];
            $now = new DateTime ( 'now' );
            $now->sub ( new DateInterval ( 'PT' . $value['minutes'] . 'M' ) );
            $value['create'] = DateTime::createFromFormat ( 'Y-m-d H:i:s' , $value['create'] , new DateTimeZone ( 'America/Sao_Paulo' ) );
            if ( $now >= $value['create'] ) {
                $cache_raw[$value['i']] = null;
                mem_put ( 'cache' , $cache_raw );

                return null;
            } else {
                return $value['data'];
            }
        } else {
            return null;
        }
    }

    function __get_mem_res () {
        if ( ! file_exists ( $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'memory.pid' ) ) {
            file_put_contents ( $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'memory.pid' , '' );
        }
        $pidfile = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'memory.pid';
        $tok = ftok ( $pidfile , chr ( 2 ) );
        return shm_attach ( $tok , 134217728 );
    }

    function __get_mem_map ( $shm_identifier ) {
        if ( ! shm_has_var ( $shm_identifier , 0 ) ) {
            $map = [0 => '__'];
            shm_put_var ( $shm_identifier , 0 , $map );
        } else {
            $map = shm_get_var ( $shm_identifier , 0 );
        }
        return $map;
    }

    function get_mem_map () {
        $shm_identifier = __get_mem_res ();
        return __get_mem_map ( $shm_identifier );
    }

    function mem_map ( $key ) {
        $shm_identifier = __get_mem_res ();
        $map = __get_mem_map ( $shm_identifier );
        $s = array_search ( $key , array_values ( $map ) );
        if ( $s === false ) {
            $map[] = $key;
            shm_put_var ( $shm_identifier , 0 , $map );
            return count ( $map ) - 1;
        } else {
            return $s;
        }
    }

    function mem_get ( $key = null ) {
        $shm_identifier = __get_mem_res ();
        $map = __get_mem_map ( $shm_identifier );
        if ( $key === null ) {
            $r = [];
            foreach ( array_values ( $map ) as $k ) {
                $r[$k] = mem_get ( $k );
            }
            return $r;
        }
        $id = mem_map ( $key );
        if ( ! shm_has_var ( $shm_identifier , $id ) ) {
            return null;
        }
        return shm_get_var ( $shm_identifier , $id );
    }

    function mem_put ( $key , $value ) {
        $shm_identifier = __get_mem_res ();
        $id = mem_map ( $key );
        return shm_put_var ( $shm_identifier , $id , $value );
    }

    function mem_has ( $key ) {
        $shm_identifier = __get_mem_res ();
        $id = mem_map ( $key );
        return shm_has_var ( $shm_identifier , $id );
    }

} else {
//    echo 'enable php_sysvshm.dll';
}