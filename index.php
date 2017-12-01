<?php

include 'pkj/server/pkjall.php' ;
$template = '' ;
//ob_start( 'ob_gzhandler' ) ;
ob_start() ;
$path = pkj_get_home( __DIR__ ) ;
$url = $path ;
$public = $url . 'public/' ;
$path = 'public/' . replace( $_SERVER[ "REQUEST_URI" ] , $path , '' ) ;
$path = replace( $path , '?' . $_SERVER[ "QUERY_STRING" ] , '' ) ;
if ( endswith( $path , "public/" ) ) {
    $path = "public/index" ;
}
$path .= '.php' ;
if ( !file_exists( $path ) ) {
    if ( file_exists( replace( $path , '.php' , '.html' ) ) ) {
        $html = file_get_contents( replace( $path , '.php' , '.html' ) ) ;
        $html = replace( $html , 'href="' , 'href="' . $url . dirname( $path ) . '/' ) ;
        $html = replace( $html , 'src="' , 'src="' . $url . dirname( $path ) . '/' ) ;
        echo $html ;
        exit() ;
    }
    $file_not_found = $path ;
    $path = "public/err_404.php" ;
}

include $path ;

if ( isset( $_POST[ "CMD" ] ) ) {
    ob_clean() ;
    include "pkj/server/pkjbind.php" ;
} else {
    if ( $template !== '' ) {
        $content = ob_get_clean() ;
        include "public/" . $template ;
        exit() ;
    }
    echo ob_get_clean() ;
}