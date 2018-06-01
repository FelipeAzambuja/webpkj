<?php
$template = 'templates/template.php';

function main() {

    $data = [
        'nome' => 'Felipe',
        'nascimento' => '26/08/1989',
        'idade' => '28',
        'email' => 'felipe@newbgp.com.br'
    ];
    $data = [
        'nome' => '',
        'nascimento' => '26/08/1989',
        'idade' => '28',
        'email' => 'felipe@a.com'
    ];

    $validator = is_valid($data, [
        'nome' => 'required',
        'nascimento' => 'required|date,d/m/Y',
        'email' => 'required|valid_email',
        'idade' => 'required|integer'
    ]);
//    vue('vue')->load('vue/itemLista.php');
//    popup(@d());
}
?>
<script>
    var lista = null;
    $(function () {
        lista = new Vue({
            el: '#main',
            data: {
                itens: [
                    {
                        id: 1,
                        texto: 'teste'
                    },
                    {
                        id: 2,
                        texto: 'teste teste'
                    }
                ]
            }
        });
    });
</script>
<form init="main" id="main" >
    
    <?php
    label_text('Nome', 'nome', 12);
    label_upload('Arquivo', 'arquivo[]', 'multiple="true"', 12);
    //falta implementar icon 
    button('<i class="fa fa-save"></i> Mostrar Nome', 'click="mostrarNome(feliz)" ', 12);

    function mostrarNome($form) {
        if (is_empty($form['nome'])) {
            focus('#nome');
            notify('O Campo nome é obrigatório');
            exit();
        }
    }
    ?>
</form>