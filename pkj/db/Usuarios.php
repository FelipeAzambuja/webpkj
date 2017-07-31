<?php

/**
 * 
 * @return	Usuarios
 */
function orm_usuarios() {
    return new Usuarios();
}

class Usuarios extends DBTable {

    var $id;
    var $nome;
    var $senha;
    var $nascimento;

    function getFields() {
        $campos = [];
        $campos[] = array("name" => "id", "type" => "integer");
        $campos[] = array("name" => "nome", "type" => "text");
        $campos[] = array("name" => "senha", "type" => "text");
        $campos[] = array("name" => "nascimento", "type" => "datetime");
        return $campos;
    }

    function getName() {
        return "usuarios";
    }

}
