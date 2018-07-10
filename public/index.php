<?php
$template = 'templates/template.php';

function main() {

//    $validator = is_valid($data, [
//        'nome' => 'required',
//        'nascimento' => 'required|date,d/m/Y',
//        'email' => 'required|valid_email',
//        'idade' => 'required|integer'
//    ]);
    $data = vue('lista')->data();
    $data['itens'][] = [
        'id' => 3,
        'texto' => 'PHP é legal'
    ];
    vue('lista')->data($data);
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
    <ul>
        <li v-for="item in itens">{{item.id}} {{item.texto}}</li>
    </ul>
    <?php
    label_text('Nome', 'nome', 12);
    label_upload('Arquivo', 'arquivo[]', 'multiple="true"', 12);
    
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