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
    $(function () {
//        Vue.component('item-lista', {
//            name: 'item-lista',
//            props: [
//                'item'
//            ],
//            template: "" + "\n<li>{{item.texto}}<\/li>\n" + ""
//        });
        Vue.load('vue/itemLista','item-lista');
        vue = new Vue({
            el: '#main',
            data: {
                itens: [
                    {
                        texto: 'teste'
                    },
                    {
                        texto: 'teste'
                    },
                    {
                        texto: 'teste'
                    }
                ]
            }
        });
    });
</script>
<form init="main" id="main" >
    <ul>
        <item-lista v-for="item in itens" v-bind:item="item"></item-lista>
    </ul>
    <?php
    label_text('Nome', 'nome', 12);
    label_upload('Arquivo', 'arquivo[]', 'multiple="true"', 12);
    button('Mostrar Nome', 'click="mostrarNome(feliz)" ', 12);

    function mostrarNome($form) {
        if (is_empty($form['nome'])) {
            focus('#nome');
            notify('O Campo nome é obrigatório');
            exit();
        }
    }
    ?>
</form>