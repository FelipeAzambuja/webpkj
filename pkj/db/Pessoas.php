<?php

class Pessoas extends DBTable {

    public $id;

    /**
     * @var string 
     */
    public $nome;
    public $telefone;
    public $senha;

    /**
     * @var datetime
     */
    public $momento;

    /**
     * @access public
     * @var float
     * @lenght 20
     * @pk false
     * @comment Saldo do usuário
     */
    public $saldo;

    /**
     * @var string
     */
    public $skype;

    /**
     * @autoload
     * @relation id = Contatos.pessoa
     * @var Contatos|array
     */
    public $contatos;

    public function get_table_name() {
        return "pessoas";
    }

}
