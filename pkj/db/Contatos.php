<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Contatos
 *
 * @author felipe
 */
class Contatos extends DBTable {

    public $id;
    public $tipo;
    public $valor;

    /**
     * 
     * @relation pessoa = Pessoas.id
     * @var Pessoas
     */
    public $pessoa;
    
    public function get_table_name() {
        return "contatos";
    }

}
