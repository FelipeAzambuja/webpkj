<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title></title>
        <?php
        import("jquery");
        import("bootstrap");
        import("bind");
        import("pkj");
        import("datatables");
        import("chosen");
        import("mask");
        import("icheck");
        ?>        
    </head>
    <body>
        <div class="container">
            <form>
                <?php
                label('Descrição');
                text('descricao', 6);
                label('Destino');
                $tabelas = query("show tables");
                $tabelas = col($tabelas, "Tables_in_ekont562_db_ek");
                combo('table_destino', $tabelas, $tabelas, "change='selecionar()'", 4);

                function selecionar($form) {
                    $desc = implode(PHP_EOL, col(query("desc $form[table_destino]"), "Field"));
                    setValue("#campos", $desc);
                }

                label('Ordem');
                number('ordem');
                check('status', 'Status');
                label('Keys');
                text('keys', 4);
                label('Periodicidade');
                $periodicidade = query("select id,concat(descricao,'  ',tempo) as 'desc' from esc_periodicidade");
                combo('id_periodicidade', col($periodicidade, 'id'), col($periodicidade, 'desc'));
                label('Prioridade');
                $prioridade = query("select id,descrição from esc_prioridade");
                combo('id_prioridade', col($prioridade, 'id'), col($prioridade, 'descrição'));
                label('Periodo');
                $periodo = query("select id,concat(descricao,' ',inicial,' ',final) as descricao from esc_periodo");
                combo('id_periodo', col($periodo, 'id'), col($periodo, 'descricao'));
                label('Contrato');
                $contrato = query("select id,concat(id_cliente,' ',descricao) as descricao from ehn_contrato");
                combo('id_tipocontrato', col($contrato, 'id'), col($contrato, 'descricao'));
                label('Horario');
                $horario = query("select id,descricao from esc_horario");
                combo('id_horario', col($horario, 'id'), col($horario, 'descricao'));
                button('Salvar', 'click="salvar()" lock', 4);

                function salvar($form) {
                    unset($form["campos"]);
                    $form['status'] = ($form['status'] === 'true') ? '1' : '0';
                    $form['script_saida'] = $_POST['script_saida'];
                    $insert = db()->insert("esc_script", $form);
                    cd($insert);
                    if(!$insert){
                        console(db()->last_error);
                    }
                }
                ?>
                <div class="col-md-10">
                    <textarea class="col-md-10" id="script_saida" style="height:500px"></textarea>
                </div>
                <div class="col-md-2">
                    <textarea class="col-md-10" id="campos" style="height:500px"></textarea>
                </div>


            </form>
        </div>
    </body>
</html>