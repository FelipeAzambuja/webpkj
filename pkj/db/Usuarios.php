<?php

/**
 * 
 * @return \Usuarios
 */
function db_usuario() {
    return new Usuario();
}

class Usuario extends DBTable {

    var $id, $nome, $senha;

    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }
    
    /**
     * verifica o nome e senha 
     * @param string $nome
     * @param string $senha
     * @return bool 
     */
    public function login($nome,$senha) {
        $senha = md5($senha);
        return $this->one("nome='$nome' and senha='$senha'") == null;
    }
    public function setSenha($senha) {
        $this->senha = md5($senha);
        return $this;
    }

    public function getName() {
        return "usuarios";
    }

    public function getFields() {

        $campos = array();
        $campos[] = array("name" => "id", "type" => "integer");
        $campos[] = array("name" => "nome", "type" => "text");
        $campos[] = array("name" => "senha", "type" => "text");
        return $campos;
    }

}
