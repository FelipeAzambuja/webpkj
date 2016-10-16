<?php
include './pkj/server/all.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php
        import("jquery");
        import("icheck");
        import("bootstrap");
        import("bind");
        import("bpopup");        
        import("mask");  
        import("chosen");      
        ?>
    </head>
    <body>
        <form class="container">
            	<?php
            	row();
            	text("chave","placeholder='Chave'",3);
            	text("valor","placeholder='Valor'",6);
            	check("ativo","Ativo");
            	radio("sexo","Masculino","sexo");
            	radio("sexo","Feminino","sexo");
            	$cidades = array("Santos","São Vicente");
                label("Cidades",2);
            	combo("cidade",$cidades,$cidades,4);
            	row();
            	row();
            	button("Ler","click='ler()' color='danger' page='pkj/lab.php'",6);
            	button("Gravar","click='gravar()' page='pkj/lab.php'",6);
            	row();
            	?>
        </form>
    </body>
</html>