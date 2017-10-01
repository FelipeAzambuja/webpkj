<?php
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
 * @var Pessoas
 */
public $pessoa;
    
    public function get_table_name() {
        return "contatos";
    }

}
