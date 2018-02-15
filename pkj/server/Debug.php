<?php
function wait() {
    return Debug::wait();
}
/**
 * Classe de debug
 *
 * @author felipe
 */
class Debug {

    public static function wait() {
        global $path ;
        $page = replace( $path , 'public/' , '' ) ;
        $page = replace( $page , '.php' , '' ) ;
        $debug = json_decode( file_get_contents( 'debug.json' ) ) ;
        if ( $page != $debug->page ) {
            return ;
        }
        while ( !$debug->continue ) {
            $watchs = [] ;
            foreach ( $debug->watchs as $w ) {
                $r = null ;
                eval( (startswith( $w , '$' ) ? 'global ' . $w . ';' : '') . '$r = ' . $w . ';' ) ;
                $watchs[ $w ] = $r ;
            }
            $debug->watchs = $watchs ;
            $debug->done = true ;
            $debug->continue = true ;
            file_put_contents( 'debug.json' , json_encode( $debug ) ) ;
            break ;
        }
    }

    public static function cmd( $page , $watchs = [] ) {
        file_put_contents( 'debug.json' , json_encode( [
            'page' => $page ,
            'continue' => false ,
            'done' => false ,
            'watchs' => $watchs
        ] ) ) ;
        $debug = json_decode( file_get_contents( 'debug.json' ) ) ;
        while ( !$debug->done ) {
            sleep( 1 ) ;
            $debug = json_decode( file_get_contents( 'debug.json' ) ) ;
        }
        unlink('debug.json');
        return $debug ;
    }

}
