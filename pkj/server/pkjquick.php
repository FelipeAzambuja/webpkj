<?php

function import ( $name ) {
  resource ()->import ( $name );
}

function jquery ( $id , $code ) {
  bind ()->jquery ( $id , $code );
}

function alert ( $msg ) {
  JS::alert ( $msg );
}

function console ( $msg ) {
  JS::console ( $msg );
}

function redirect ( $page , $data = "" ) {
  JS::redirect ( $page , $data );
}

function popup ( $msg , $id = "" ) {
  JS::popup ( $msg , $id );
}

function popup_close ( $id = "" ) {
  JS::popup_close ( $id );
}

function value ( $id , $value ) {
  setValue ( $id , $value );
}

function setValue ( $id , $value ) {
  bind ()->setValue ( $id , $value );
}

function html ( $id , $html ) {
  bind ()->setHtml ( $id , $html );
}

function setHtml ( $id , $html ) {
  bind ()->setHtml ( $id , $html );
}

function setText ( $id , $text ) {
  bind ()->setText ( $id , $text );
}

function append ( $id , $text ) {
  bind ()->append ( $id , $text );
}

function setEnable ( $id ) {
  bind ()->setEnable ( $id );
}

function setDisable ( $id ) {
  bind ()->setDisable ( $id );
}

function show ( $id ) {
  bind ()->show ( $id );
}

function hide ( $id ) {
  bind ()->hide ( $id );
}

function focus ( $id ) {
  bind ()->focus ( $id );
}

function setInterval ( $function , $time , $parameters = array () , $page = "" ) {
  bind ()->setInterval ( $function , $time , $parameters , $page );
}

function setTimeout ( $function , $time , $parameters = array () , $page = "" ) {
  bind ()->setTimeout ( $function , $time , $parameters , $page );
}

function stopInterval ( $function ) {
  bind ()->stopInterval ( $function );
}

function stopTimeout ( $function ) {
  bind ()->stopTimeout ( $function );
}

function tpl ( $file , $data = null , $cache = true ) {
  return smarty ( $file , $data , $cache );
}

function smarty ( $file , $data = null , $cache = true ) {
  $smarty = new Smarty();
  if ( $data != null ) {
    foreach ( $data as $key => $value ) {
      $smarty->assign ( $key , $value );
    }
  }
  $smarty->setTemplateDir ( __DIR__ . '/smarty/templates' );
  $smarty->setCompileDir ( __DIR__ . '/smarty/templates_c' );
  $smarty->setCacheDir ( __DIR__ . '/smarty/cache' );
  $smarty->setConfigDir ( __DIR__ . '/smarty/configs' );
  $mod = (($cache) ? "string" : "eval");
  ob_start ();
  if ( is_file ( $file ) ) {
    $smarty->display ( $mod . ":" . file_get_contents ( $file ) );
  } else {
    $smarty->display ( $mod . ":" . $file );
  }
  $html = str_replace ( array ( "\r" , "\n" ) , "" , ob_get_contents () );
  ob_end_clean ();
  return $html;
}
