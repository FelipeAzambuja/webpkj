<?php
require_once __DIR__ . '/pkj/server/all.php';

function init () {
  tabela ();
}

function form_adicionar ( $form ) {
  ob_start ();
  ?>
  <form>
      <?php
      label ( "Id" , 4 );
      text ( "id" , 8 );
      label ( "Nome" , 4 );
      text ( "nome" , 8 );
      label ( "Imagem" , 4 );
      upload ( "imagem" , 8 );
      label ( "Nivel" , 4 );
      text ( "nivel" , 8 );
      label ( "Frase" , 4 );
      text ( "frase" , 8 );
      label ( "Senha" , 4 );
      text ( "senha" , 8 );
      label ( "Email" , 4 );
      text ( "email" , 8 );
      label ( "Lembrar" , 4 );
      text ( "lembrar" , 8 );
      label ( "Alterado" , 4 );
      text ( "alterado" , 8 );
      button ( "Adicionar" , "click='adicionar()'" , 12 );
      ?>
  </form>
  <?php
  $html = ob_get_clean ();
  popup ( $html );
  bindUpdate ();
}

function form_editar ( $form ) {
  $usuarios = one(query("select * from usuarios where id=$form[id]"));
  ob_start ();
  ?>
  <form>
      <?php
      label ( "Id" , 4 );
      text ( "id","value='$usuarios->id'" , 8 );
      label ( "Nome" , 4 );
      text ( "nome","value='$usuarios->nome'" , 8 );
      label ( "Imagem" , 4 );
      upload ( "imagem", 8 );
      label ( "Nivel" , 4 );
      text ( "nivel","value='$usuarios->nivel'" , 8 );
      label ( "Frase" , 4 );
      text ( "frase","value='$usuarios->frase'" , 8 );
      label ( "Senha" , 4 );
      text ( "senha","value='$usuarios->senha'" , 8 );
      label ( "Email" , 4 );
      text ( "email","value='$usuarios->email'" , 8 );
      label ( "Lembrar" , 4 );
      text ( "lembrar","value='$usuarios->lembrar'" , 8 );
      label ( "Alterado" , 4 );
      text ( "alterado","value='$usuarios->alterado'" , 8 );
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
  $usuarios = array ();
  $sql["id"] = $form["id"];
  $sql["nome"] = $form["nome"];
  $sql["imagem"] = $form["imagem"];
  $sql["nivel"] = $form["nivel"];
  $sql["frase"] = $form["frase"];
  $sql["senha"] = $form["senha"];
  $sql["email"] = $form["email"];
  $sql["lembrar"] = $form["lembrar"];
  $sql["alterado"] = $form["alterado"];
  $sql = SQLinsert("usuarios", $sql);
  if(!query($sql)){
    alert(db_get_error().PHP_EOL.$sql);
    exit();
  }
  tabela ();
  popup_close ();
}

function editar ( $form ) {
  $usuarios = array ();
  $sql["id"] = $form["id"];
  $sql["nome"] = $form["nome"];
  $sql["imagem"] = $form["imagem"];
  $sql["nivel"] = $form["nivel"];
  $sql["frase"] = $form["frase"];
  $sql["senha"] = $form["senha"];
  $sql["email"] = $form["email"];
  $sql["lembrar"] = $form["lembrar"];
  $sql["alterado"] = $form["alterado"];
  $sql = SQLupdate("usuarios", $sql,"id=$form[id]");
  if(!query($sql)){
    alert(db_get_error().PHP_EOL.$sql);
    exit();
  }
  tabela ();
  popup_close ();
}

function remover ( $form ) {
  query ( "delete from usuarios where id=$form[id]" );
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
  	    <th>Id</th>
  	    <th>Nome</th>
  	    <th>Imagem</th>
  	    <th>Nivel</th>
  	    <th>Frase</th>
  	    <th>Senha</th>
  	    <th>Email</th>
  	    <th>Lembrar</th>
  	    <th>Alterado</th>
  	    <th>Opções</th>
  	</tr>
      </thead>
      <tbody>
	  <?php foreach ( query("select id,nome,imagem,nivel,frase,senha,email,lembrar,alterado from usuarios") as $linha ): ?>
    	<tr>
    	    <td><?php hidden ( "id" , $linha->id , true ) ?></td>
    	    <td><?= $linha->nome ?></td>
    	    <td><img src="data:image;base64,<?= base64_encode($linha->imagem) ?>" /></td>
    	    <td><?= $linha->nivel ?></td>
    	    <td><?= $linha->frase ?></td>
    	    <td><?= $linha->senha ?></td>
    	    <td><?= $linha->email ?></td>
    	    <td><?= $linha->lembrar ?></td>
               <?php $linha->alterado = cdate($linha->alterado)->format("d/m/Y H:i:s"); ?>
    	    <td><?= $linha->alterado ?></td>
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
	    <div class="col-sm-12" style="overflow: auto;margin-top: 15px" id="tabela">
	    </div>
	</div>
    </body>
</html>