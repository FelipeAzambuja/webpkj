<?php

include 'pkj/server/pkjall.php' ;
$template = '' ;
ob_start() ;
$path = pkj_get_home( __DIR__ ) ;
$url = $path ;
$public = $url . 'public/' ;

if ( $path !== '/' ) {
    $path = 'public/' . replace( $_SERVER[ "REQUEST_URI" ] , $path , '' ) ;
} else {
    $path = 'public/' . substr( $_SERVER[ "REQUEST_URI" ] , 1 ) ;
}

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
if ( isset( $_SERVER[ 'HTTPS' ] ) &&
        ($_SERVER[ 'HTTPS' ] == 'on' || $_SERVER[ 'HTTPS' ] == 1) ||
        isset( $_SERVER[ 'HTTP_X_FORWARDED_PROTO' ] ) &&
        $_SERVER[ 'HTTP_X_FORWARDED_PROTO' ] == 'https' ) {
    $protocol = 'https' ;
} else {
    $protocol = 'http' ;
}
$url = "{$protocol}://{$_SERVER[ 'HTTP_HOST' ]}" . $url ;
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