<?php

/**
 * 
 * @return \Pessoas
 */
function orm_pessoas() {
    return new Pessoas();
}

class Pessoas extends DBTable {

    var $id, $nome, $telefone, $momento;

    function setNome($nome) {
        if($this->exists("nome='$nome'")){
            throw new Exception("Já existe um cadastro com esse nome");
        }
        $this->nome = $nome;
        return $this;
    }

    function setTelefone($telefone) {
        if($this->exists("telefone='$telefone'")){
            throw new Exception("Já existe um cadastro com esse nome");
        }
        $this->telefone = $telefone;
        return $this;
    }

    function setMomento($momento) {
        throw new Exception("Forçar o momento de cadastro é ilegal");
        $this->momento = null;
        return $this;
    }

    public function getName() {
        return "pessoas";
    }

    public function getFields() {
        $campos = array();
        $campos[] = array("name" => "id", "type" => "integer");
        $campos[] = array("name" => "nome", "type" => "text");
        $campos[] = array("name" => "telefone", "type" => "text");
        $campos[] = array("name" => "momento", "type" => "datetime default(datetime('now','localtime'))");
        return $campos;
    }

}
