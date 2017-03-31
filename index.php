<?php
require_once __DIR__ . '/pkj/server/all.php';

//orm_usuario()->create();
//$id = orm_usuario ()->setNome ( "Felipe" )->setSenha ( "123" )->setNivel ( "Administrador" )->save ();
function init () {
  tabela ();
}

function form_adicionar ( $form ) {
  ob_start ();
  ?>
  <form>
      <?php
      label ( "Nome" , 4 );
      text ( "nome" , 8 );
      label ( "Senha" , 4 );
      password ( "senha" , 8 );
      label ( "Nivel" , 4 );
      text ( "nivel" , 8 );
      button ( "Adicionar" , "click='adicionar()'" , 12 );
      ?>
  </form>
  <?php
  $html = ob_get_clean ();
  popup ( $html );
  bindUpdate ();
}

function form_editar ( $form ) {
  $usuario = orm_usuario ()->one ( "id=$form[id]" );
  ob_start ();
  ?>
  <form>
      <?php
      hidden ( "id" , $usuario->id );
      label ( "Nome" , 4 );
      text ( "nome" , "value='$usuario->nome'" , 8 );
      label ( "Senha" , 4 );
      password ( "senha" , 8 );
      label ( "Nivel" , 4 );
      text ( "nivel" , "value='$usuario->nivel'" , 8 );
      button ( "Editar" , "click='editar()'" , 6 );
      button ( "Remover" , "click='remover()' color='danger'" , 6 );
      ?>
  </form>
  <?php
  $html = ob_get_clean ();
  popup ( $html );
  bindUpdate ();
}

function adicionar ( $form ) {
  $usuario = orm_usuario ();
  $usuario->setNome ( $form["nome"] );
  $usuario->setNivel ( $form["nivel"] );
  $usuario->setSenha ( $form["senha"] );
  $usuario->save ();
  tabela ();
  popup_close ();
}

function editar ( $form ) {
  $usuario = orm_usuario ();
  $usuario->setNome ( $form["nome"] );
  $usuario->setNivel ( $form["nivel"] );
  $usuario->setSenha ( $form["senha"] );
  $usuario->update ( "id=$form[id]" );
  tabela ();
  popup_close ();
}

function remover ( $form ) {
  orm_usuario ()->delete ( "id=$form[id]" );
  tabela ();
  popup_close ();
}

function tabela () {
  ob_start ();
  ?>
  <form>
      <input type="button" color="success" value="Adicionar" click="form_adicionar()" />
  </form>
  <table class="datatables">
      <thead>
  	<tr>
  	    <th>ID</th>
  	    <th>Nome</th>
  	    <th>Senha</th>
  	    <th>Nivel</th>
  	    <th>Opções</th>
  	</tr>
      </thead>
      <tbody>
	  <?php foreach ( orm_usuario ()->query () as $linha ): ?>
    	<tr>
    	    <td><?php hidden ( "id" , $linha->id , true ) ?></td>
    	    <td><?= $linha->nome ?></td>
    	    <td><?= str_repeat ( "*" , len ( $linha->senha ) ) ?></td>
    	    <td><?= $linha->nivel ?></td>
    	    <td><?php button ( "Editar" , "click='form_editar()'" , 12 ) ?></td>
    	</tr>
	  <?php endforeach; ?>
      </tbody>
  </table>
  <?php
  $html = ob_get_clean ();
  html ( "#tabela" , $html );
  bindUpdate ();
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
	import ( "mask" );
	import ( "datatables" );
	import ( "bpopup" );
	?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
	<div class="container">
	    <div class="col-sm-12" style="overflow: auto;margin-top: 15px" id="tabela">;;
	    </div>
	</div>
    </body>
</html>