<?php
require_once __DIR__ . '/../server/all.php';
//orm_usuario ()->drop ();
//orm_usuario ()->create ();
?>
<html>
    <head>
        <meta charset="utf-8" />
        <title></title>
	<?php
	import ( "jquery" );
	import ( "bootstrap" );
	import ( "icheck" );
	import ( "bind" );
	?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
	<div class="container">
	    <form>
		<?php
		label ( "Tabela" , 2 );
		text ( "tabela" , 4 );
//		check ( "orm" , "Usar ORM" , "" , 3 );
		button ( "Gerar" , "click='gerar()' lock" , 6 );

		function gerar ( $form ) {
		  $info = DBTable::tableInfo ( $form["tabela"] );
		  $tabela = $form["tabela"];
		  $s = '' . PHP_EOL;
		  $s .= '<?php' . PHP_EOL;
		  $s .= 'require_once __DIR__ . \'/pkj/server/all.php\';' . PHP_EOL;
		  $s .= '' . PHP_EOL;
//		  $s .= '//orm_usuario()->create();' . PHP_EOL;
//		  $s .= '//$id = orm_usuario ()->setNome ( "Felipe" )->setSenha ( "123" )->setNivel ( "Administrador" )->save ();' . PHP_EOL;
		  $s .= 'function init () {' . PHP_EOL;
		  $s .= '  tabela ();' . PHP_EOL;
		  $s .= '}' . PHP_EOL;
		  $s .= '' . PHP_EOL;
		  $s .= 'function form_adicionar ( $form ) {' . PHP_EOL;
		  $s .= '  ob_start ();' . PHP_EOL;
		  $s .= '  ?>' . PHP_EOL;
		  $s .= '  <form>' . PHP_EOL;
		  $s .= '      <?php' . PHP_EOL;
		  foreach ( $info as $i ) {
		    if ( $i->name == "id" ) {
		      continue;
		    }
		    $s .= '      label ( "' . ucfirst ( $i->name ) . '" , 4 );' . PHP_EOL;
		    if ( $i->type == "blob" ) {
		      $s .= '      upload ( "' . lcase ( $i->name ) . '" , 8 );' . PHP_EOL;
		    } else {
		      $s .= '      text ( "' . lcase ( $i->name ) . '" , 8 );' . PHP_EOL;
		    }
		  }
		  $s .= '      button ( "Adicionar" , "click=\'adicionar()\'" , 12 );' . PHP_EOL;
		  $s .= '      ?>' . PHP_EOL;
		  $s .= '  </form>' . PHP_EOL;
		  $s .= '  <?php' . PHP_EOL;
		  $s .= '  $html = ob_get_clean ();' . PHP_EOL;
		  $s .= '  popup ( $html );' . PHP_EOL;
		  $s .= '  bindUpdate ();' . PHP_EOL;
		  $s .= '}' . PHP_EOL;
		  $s .= '' . PHP_EOL;
		  $s .= 'function form_editar ( $form ) {' . PHP_EOL;
		  $s .= '  $' . $tabela . ' = one(query("select * from ' . $tabela . ' where id=$form[id]"));' . PHP_EOL;
		  $s .= '  ob_start ();' . PHP_EOL;
		  $s .= '  ?>' . PHP_EOL;
		  $s .= '  <form>' . PHP_EOL;
		  $s .= '      <?php' . PHP_EOL;
		  foreach ( $info as $i ) {
		    if ( $i->name == "id" ) {
		      continue;
		    }
		    $s .= '      label ( "' . ucfirst ( $i->name ) . '" , 4 );' . PHP_EOL;
		    if ( $i->type == "blob" ) {
		      $s .= '      upload ( "' . lcase ( $i->name ) . '", 8 );' . PHP_EOL;
		    } else {
		      $s .= '      text ( "' . lcase ( $i->name ) . '","value=\'$' . $tabela . '->' . lcase ( $i->name ) . '\'" , 8 );' . PHP_EOL;
		    }
		  }
		  $s .= '      button ( "Editar" , "click=\'editar()\'" , 6 );' . PHP_EOL;
		  $s .= '      button ( "Remover" , "click=\'remover()\' color=\'danger\'" , 6 );' . PHP_EOL;
		  $s .= '      ?>' . PHP_EOL;
		  $s .= '  </form>' . PHP_EOL;
		  $s .= '  <?php' . PHP_EOL;
		  $s .= '  $html = ob_get_clean ();' . PHP_EOL;
		  $s .= '  popup ( $html );' . PHP_EOL;
		  $s .= '  bindUpdate ();' . PHP_EOL;
		  $s .= '}' . PHP_EOL;
		  $s .= '' . PHP_EOL;
		  $s .= 'function adicionar ( $form ) {' . PHP_EOL;
		  $s .= '  $' . $tabela . ' = array ();' . PHP_EOL;
		  foreach ( $info as $i ) {
		    $s .= '  $sql["' . lcase ( $i->name ) . '"] = $form["' . lcase ( $i->name ) . '"];' . PHP_EOL;
		  }
		  $s .= '  $sql = SQLinsert("' . $tabela . '", $sql);' . PHP_EOL;
		  $s .= '  if(!query($sql)){' . PHP_EOL;
		  $s .= '    alert(db_get_error().PHP_EOL.$sql);' . PHP_EOL;
		  ;
		  $s .= '    exit();' . PHP_EOL;
		  $s .= '  }' . PHP_EOL;
		  $s .= '  tabela ();' . PHP_EOL;
		  $s .= '  popup_close ();' . PHP_EOL;
		  $s .= '}' . PHP_EOL;
		  $s .= '' . PHP_EOL;
		  $s .= 'function editar ( $form ) {' . PHP_EOL;
		  $s .= '  $' . $tabela . ' = array ();' . PHP_EOL;
		  foreach ( $info as $i ) {
		    $s .= '  $sql["' . lcase ( $i->name ) . '"] = $form["' . lcase ( $i->name ) . '"];' . PHP_EOL;
		  }
		  $s .= '  $sql = SQLupdate("' . $tabela . '", $sql,"id=$form[id]");' . PHP_EOL;
		  $s .= '  if(!query($sql)){' . PHP_EOL;
		  $s .= '    alert(db_get_error().PHP_EOL.$sql);' . PHP_EOL;
		  ;
		  $s .= '    exit();' . PHP_EOL;
		  $s .= '  }' . PHP_EOL;
		  $s .= '  tabela ();' . PHP_EOL;
		  $s .= '  popup_close ();' . PHP_EOL;
		  $s .= '}' . PHP_EOL;
		  $s .= '' . PHP_EOL;
		  $s .= 'function remover ( $form ) {' . PHP_EOL;
		  $s .= '  query ( "delete from ' . $tabela . ' where id=$form[id]" );' . PHP_EOL;
		  $s .= '  tabela ();' . PHP_EOL;
		  $s .= '  popup_close ();' . PHP_EOL;
		  $s .= '}' . PHP_EOL;
		  $s .= '' . PHP_EOL;
		  $s .= 'function tabela () {' . PHP_EOL;
		  $s .= '  ob_start ();' . PHP_EOL;
		  $s .= '  ?>' . PHP_EOL;
		  $s .= '  <form>' . PHP_EOL;
		  $s .= '      <input type="button" color="success" value="Adicionar" click="form_adicionar()" />' . PHP_EOL;
		  $s .= '  </form>' . PHP_EOL;
		  $s .= '  <table class="datatables">' . PHP_EOL;
		  $s .= '      <thead>' . PHP_EOL;
		  $s .= '  	<tr>' . PHP_EOL;
		  foreach ( $info as $i ) {
		    $s .= '  	    <th>' . ucfirst ( $i->name ) . '</th>' . PHP_EOL;
		  }
		  $s .= '  	    <th>Opções</th>' . PHP_EOL;
		  $s .= '  	</tr>' . PHP_EOL;
		  $s .= '      </thead>' . PHP_EOL;
		  $s .= '      <tbody>' . PHP_EOL;
		  $s .= '	  <?php foreach ( query("select ' . implode ( "," , col ( $info , "name" ) ) . ' from ' . $tabela . '") as $linha ): ?>' . PHP_EOL;
		  $s .= '    	<tr>' . PHP_EOL;
		  $s .= '    	    <td><?php hidden ( "id" , $linha->id , true ) ?></td>' . PHP_EOL;
		  foreach ( $info as $i ) {
		    if ( lcase ( $i->name ) == "id" ) {
		      continue;
		    }
		    if ( $i->type == "blob" ) {
		      if ( conf::$servidor == "postgre" ) {
			$s .= '    	    <td><img src="data:image;base64,<?= base64_encode(pg_unescape_bytea($linha->' . lcase ( $i->name ) . ')) ?>" /></td>' . PHP_EOL;
		      } else {
			$s .= '    	    <td><img src="data:image;base64,<?= base64_encode($linha->' . lcase ( $i->name ) . ') ?>" /></td>' . PHP_EOL;
		      }
		    } else if ( $i->type == "date" ) {
		      $s .= '               <?php $linha->' . lcase ( $i->name ) . ' = cdate($linha->' . lcase ( $i->name ) . ')->format("d/m/Y"); ?>' . PHP_EOL;
		      $s .= '    	    <td><?= $linha->' . lcase ( $i->name ) . ' ?></td>' . PHP_EOL;
		    } else if ( $i->type == "datetime" ) {
		      $s .= '               <?php $linha->' . lcase ( $i->name ) . ' = cdate($linha->' . lcase ( $i->name ) . ')->format("d/m/Y H:i:s"); ?>' . PHP_EOL;
		      $s .= '    	    <td><?= $linha->' . lcase ( $i->name ) . ' ?></td>' . PHP_EOL;
		    } else {
		      $s .= '    	    <td><?= $linha->' . lcase ( $i->name ) . ' ?></td>' . PHP_EOL;
		    }
		  }

		  $s .= '    	    <td><?php button ( "Editar" , "click=\'form_editar()\'" , 12 ) ?></td>' . PHP_EOL;
		  $s .= '    	</tr>' . PHP_EOL;
		  $s .= '	  <?php endforeach; ?>' . PHP_EOL;
		  $s .= '      </tbody>' . PHP_EOL;
		  $s .= '  </table>' . PHP_EOL;
		  $s .= '  <?php' . PHP_EOL;
		  $s .= '  $html = ob_get_clean ();' . PHP_EOL;
		  $s .= '  html ( "#tabela" , $html );' . PHP_EOL;
		  $s .= '  bindUpdate ();' . PHP_EOL;
		  $s .= '}' . PHP_EOL;
		  $s .= '?>' . PHP_EOL;
		  $s .= '<html>' . PHP_EOL;
		  $s .= '    <head>' . PHP_EOL;
		  $s .= '        <meta charset="utf-8" />' . PHP_EOL;
		  $s .= '        <title></title>' . PHP_EOL;
		  $s .= '	<?php' . PHP_EOL;
		  $s .= '	import ( "jquery" );' . PHP_EOL;
		  $s .= '	import ( "bootstrap" );' . PHP_EOL;
		  $s .= '	import ( "bind" );' . PHP_EOL;
		  $s .= '	import ( "mask" );' . PHP_EOL;
		  $s .= '	import ( "datatables" );' . PHP_EOL;
		  $s .= '	import ( "bpopup" );' . PHP_EOL;
		  $s .= '	?>' . PHP_EOL;
		  $s .= '	<meta name="viewport" content="width=device-width, initial-scale=1">' . PHP_EOL;
		  $s .= '    </head>' . PHP_EOL;
		  $s .= '    <body>' . PHP_EOL;
		  $s .= '	<div class="container">' . PHP_EOL;
		  $s .= '	    <div class="col-sm-12" style="overflow: auto;margin-top: 15px" id="tabela">' . PHP_EOL;
		  $s .= '	    </div>' . PHP_EOL;
		  $s .= '	</div>' . PHP_EOL;
		  $s .= '    </body>' . PHP_EOL;
		  $s .= '</html>' . PHP_EOL;

		  html ( "#saida" , $s );
		}
		?>
	    </form>
	    <textarea id="saida" style="width: 100%;height:200px"></textarea>
	</div>
    </body>
</html>