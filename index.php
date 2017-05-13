<?php

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
	import ( "vuejs" );
	?>
    </head>
    <body>
	
	<div class="container" id="home">
	    <?php
	    text ( "nome" , "v-model='nome' placeholder='Seu nome'" , 6 );
	    button ( "Adicionar" , "v-on:click='adicionar' " , 6 );
	    ?>
	</div>
	<script>
	  Vue.use(Bind);
	  Vue.bind.router = "http://localhost/webpkj/pkj/server/all.php";
          var home = new Vue({
              el: '#home',
              data: {
                  nome: '',
                  lista_nomes: []
              },
              methods: {
                  adicionar: function (e) {
                      this.lista_nomes.push(this.nome);
                      $(this.$el).find("#nome").focus();
		      this.$bind.call("index.php","click",{
			a:"b",
			lista:this.lista_nomes
		      });
		      this.nome = '';
                  }
              }
          });
	</script>
    </body>
</html>