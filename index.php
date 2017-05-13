<?php

function init () {
  c ( "iniciando" );
}

function click ( $form ) {
//  setDisable($id)
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
	<div class="container">
	    <form>
		<?php
		button ( "Adicionar" , "click='click()' lock" , 12 );
		?>
	    </form>
	</div>
    </body>
</html>