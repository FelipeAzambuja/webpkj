<?php

class Usuarios extends DBTable {

  var $id,$nome,$telefone;
  public function getName () { return ""; }
  //    function getFields() {
//        $campos = array();
//        $campos[] = array("name" => "id", "type" => "integer");
//        $campos[] = array("name" => "tipo", "type" => "text");
//        $campos[] = array("name" => "valor", "type" => "text");
//        $campos[] = array("name" => "pessoa", "type" => "integer");
//        $campos[] = array("name" => "usuario", "type" => "integer");
//        return $campos;
//    }
 public function getFields () {
   parent::getFields ();
 }
}