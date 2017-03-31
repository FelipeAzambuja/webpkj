<?php

/**
 * 
 * @return \Usuario
 */
function orm_usuario () {
  return new Usuario();
}

class Usuario extends DBTable {

  public $id , $nome , $senha , $nivel , $imagem , $momento;

  public function getFields () {
    $campos = array ();
    $campos[] = array ( "name" => "id" , "type" => "integer" );
    $campos[] = array ( "name" => "nome" , "type" => "text" );
    $campos[] = array ( "name" => "senha" , "type" => "text" );
    $campos[] = array ( "name" => "nivel" , "type" => "text" );
    $campos[] = array ( "name" => "imagem" , "type" => "image" );
    $campos[] = array ( "name" => "momento" , "type" => "datetime" );
    return $campos;
  }

  public function setId ( $id ) {
    $this->id = $id;
    return $this;
  }

  public function setNome ( $nome ) {
    $this->nome = $nome;
    return $this;
  }

  public function setSenha ( $senha ) {
    $this->senha = $senha;
    return $this;
  }

  public function setNivel ( $nivel ) {
    $this->nivel = $nivel;
    return $this;
  }

  public function setImagem ( $imagem ) {
    $this->imagem = $imagem;
    return $this;
  }

  public function setMomento ( $momento ) {
    $this->momento = $momento;
    return $this;
  }

  public function getName () {
    return "usuarios";
  }

}
