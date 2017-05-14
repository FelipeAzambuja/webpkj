<!doctype html>
<html>
    <head>
	<meta charset="UTF-8">
	<title></title>
	<?php
	import ( "jquery" );
	import ( "bootstrap" );
	import ( "bind" );
	?>
    </head>
    <body>
	<form>
	    <?php
	    text ( "tabela" , "placeholder='Nome da tabela'" , 10 );
	    button ( "Gerar" , "click='gerar()' lock" , 2 );

	    function gerar ( $form ) {
	      $tabela = $form["tabela"];
	      $s = '';
	      $info = DBTable::tableInfo ( $tabela );
	      $s .= 'class ' . ucfirst ( $tabela ) . " extends DBTable { " . PHP_EOL;
	      $fields = col ( $info , "name" );

	      $s .= '    var $' . implode ( ' ,$' , $fields ) . ' ;' . PHP_EOL;
	      $s .= '    public function getName () { return "' . $tabela . '"; }' . PHP_EOL;
	      $s .= '    function getFields() {' . PHP_EOL;
	      $s .= '        $campos = array();' . PHP_EOL;
	      foreach ( $info as $i ) {
		$s .= '        $campos[] = array("name"=>"' . $i->name . '","type"=>"' . $i->type . '");' . PHP_EOL;
	      }
	      $s .= '        return $campos;' . PHP_EOL;
	      $s .= '    }' . PHP_EOL;
	      $s .= '}' . PHP_EOL;
	      html ( "#out" , nl2br ( $s ) );
	    }
	    ?>
	</form>
	<div id="out">

	</div>
    </body>
</html>