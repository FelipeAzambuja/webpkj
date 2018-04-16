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
//    cd($validator);
    popup(@d($validator));
//    model_usuarios()->first();
//    $rs = model_usuarios()->last();
//    $rs = sql()->table('usuarios')->group('nome')->orderby('id')->having('1 = 1')->get();
//    cd($rs);
}
?>
<form init="main" >
    <?php
    label_text('Nome', 'nome', 12);
    label_upload('Arquivo', 'arquivo[]', 'multiple="true"', 12);
    button('Mostrar Nome', 'click="mostrarNome" ', 12);

    function mostrarNome($form) {
        if (is_empty($form['nome'])) {
            focus('#nome');
            notify('O Campo nome é obrigatório');
            exit();
        }
    }
    ?>
</form>

<?php
