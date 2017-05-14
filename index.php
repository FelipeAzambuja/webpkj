<?php

s ( DBTable::tableInfo ( "usuarios" ) );

function init () {
  c ( "iniciando" );
}

function click ( $form ) {
  cd ( $form );
}
?>
<!doctype html>
<html >
    <head>
	<meta charset="UTF-8">
	<title>Document</title>
	<?php
	import ( "jquery" );
	import ( "bootstrap" );
	import ( "bind" );
	?>
    </head>
    <body>
	<div class="container" id="home">
<?php
s ( query ( "select datetime()" ) )
?>
	</div>
    </body>
</html>