<?php
$template = "templates/template.php";

?>
<?php

function init() {
}

?>
<form class="row" >
    <?php
label_text("Nome", "nome", 6);
label_text("Sobrenome", "sobrenome", 6);
label_combo("Estado", "estado",["solido", "liquido"],["Solido", "Liquido"], 6);
label_upload("Foto", "foto", 6);
label_password("Senha", "senha", 12);
label_calendar("Nascimento", "nascimento", 3);
label_mask("CPF", "cpf", "999.999.999-99", 3);
label_money("Saldo", "saldo", 3);
label_number("Idade", "idade", 3);
check("estado", "Vivo", "", 3);
radio("sexo", "Masculino", "sexo", 3);
radio("sexo", "Feminino", "sexo", 3);
button("Teste", "click='teste()'  lock", 3);

function teste($form) {
	alert($form["nome"]);
}

?>
</form>
<table class="datatables" ajax="<?= $url ?>ajax/pessoas" >
<!--<table class="datatables"  >-->
    <thead>
        <tr>
            <th>id</th>
            <th>Nome</th>
        </tr>
    </thead>
    <tbody >
	<?php

foreach(query("select * from a")as $a)
	: 
?>
        <tr>
            <td>asdsad</td>
            <td>dsadas</td>
        </tr>
	<?php
endforeach;

?>
    </tbody>
</table>