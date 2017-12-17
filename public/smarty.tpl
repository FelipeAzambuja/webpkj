{extends file="public/templates/template.tpl"}
{block "conteudo"}
    <form class="row" >
        {label_text("Nome","nome",6)}
        {label_text("Sobrenome","sobrenome",6)}
        {label_combo("Estado" , "estado" , [ "solido" , "liquido" ] , [ "Solido" , "Liquido" ] , 6 )}
        {label_upload( "Foto" , "foto" , 6 )}
        {label_password( "Senha" , "senha" , 12 )}
        {label_calendar( "Nascimento" , "nascimento" , 3 ) }
        {label_mask( "CPF" , "cpf" , "999.999.999-99" , 3 )}
        {label_money( "Saldo" , "saldo" , 3 )}
        {label_number( "Idade" , "idade" , 3 )}
        {check( "estado" , "Vivo" , "" , 3 )}
        {radio( "sexo" , "Masculino" , "sexo" , 3 )}
        {radio( "sexo" , "Feminino" , "sexo" , 3 )}
        {button( "Teste" , "click='teste()'  lock" , 2 )}
        {button( "Teste" , "click='teste2()'  lock" , 1 )}
        {php}

        function teste( $form ) {
            alert( $form[ "nome" ] ) ;
        }
        {/php}
    </form>

    <table class="datatables" ajax="{$url}ajax/pessoas" >
        <thead>
            <tr>
                <th>id</th>
                <th>Nome</th>
            </tr>
        </thead>
        <tbody >

        </tbody>
    </table>
    {foreach $pessoas as $pessoa}
        {$pessoa->id}
    {/foreach}
{/block}



