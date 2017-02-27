<?php
require_once __DIR__ . '/pkj/server/all.php';

/**
 * 
 * @return \Usuario
 */
function orm_usuario () {
    return new Usuario();
}

class Usuario extends DBTable {

    public $id;
    public $nome;
    public $imagem;
    public $nivel;
    public $frase;
    public $senha;
    public $email;
    public $lembrar;

    function setId ( $id ) {
	$this->id = $id;
	return $this;
    }

    function setNome ( $nome ) {
	$this->nome = $nome;
	return $this;
    }

    /**
     * 
     * @param type $imagem
     * @return $this
     */
    function setImagem ( $imagem ) {
//	$this->imagem = $imagem;
	return parent::setImagem ( $imagem );//chamar o fantasma
    }

    function setNivel ( $nivel ) {
	$this->nivel = $nivel;
	return $this;
    }

    function setFrase ( $frase ) {
	$this->frase = $frase;
	return $this;
    }

    function setSenha ( $senha ) {
	$this->senha = $senha;
	return $this;
    }

    function setEmail ( $email ) {
	$this->email = $email;
	return $this;
    }

    function setLembrar ( $lembrar ) {
	$this->lembrar = $lembrar;
	return $this;
    }

    function getFields () {
	$campos = array ();
	$campos[] = array ( "name" => "id" , "type" => "integer" );
	$campos[] = array ( "name" => "nome" , "type" => "text" );
	$campos[] = array ( "name" => "imagem" , "type" => "image" );
	$campos[] = array ( "name" => "nivel" , "type" => "text" );
	$campos[] = array ( "name" => "frase" , "type" => "text" );
	$campos[] = array ( "name" => "senha" , "type" => "text" );
	$campos[] = array ( "name" => "email" , "type" => "text" );
	$campos[] = array ( "name" => "lembrar" , "type" => "text" );
	return $campos;
    }

    public function getName () {
	return "usuarios";
    }

}
?>
<html>
    <head>
        <meta charset="utf-8" />
        <title></title>
	<?php
	import ( "jquery" );
	import ( "bootstrap" );
	import ( "bind" );
	import ( "ui" );
	import ( "mask" );
	?>
    </head>
    <body>
        <div class="container-fluid">
	    <?php // s ( query ( "select * from usuarios" ) );  ?>
	    <?php file_put_contents ( "antiga.txt" , one ( orm_usuario ()->query ( "id=1" ) )->imagem ) ?>
	    <?php
	    orm_usuario ()->setImagem ( null )->update ( "id=1");
	    
	    ?>
	    <img src="<?= orm_usuario ()->one ("id=1")->imagem ?>">
        </div>
    </body>
</html>
