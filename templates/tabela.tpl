<div class="row">
    <div class="col-md-12 " >
        <table class="datatables datatables-responsive table table-striped table-bordered ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Skype</th>
                    <th>Remover</th>
                </tr>
            </thead>
            <tbody>
                {foreach $pessoas as $d}
                    <tr>
                        <td>{hidden('id',$d->id,true)}</td>
                        <td>{$d->nome}</td>
                        <td>{$d->telefone}</td>
                        <td>{$d->skype}</td>
                        <td>{button('Remover',"color='danger' click='remover()'",12)}</td>
                    </tr>    
                {/foreach}
            </tbody>
        </table>
    </div>
</div>