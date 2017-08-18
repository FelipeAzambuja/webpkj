<?php
/**
 * 
 * @return	Pessoas
 */
function orm_pessoas(){
	return new Pessoas();
}
class Pessoas extends DBTable {
	
	var $id;
	var $nome;
	var $telefone;
	var $email;
	var $senha;
	var $idade;
	var $saldo;
	
	function getFields(){
		$campos = [];
		$campos[] = array("name"=>"id","type"=>"integer");
		$campos[] = array("name"=>"nome","type"=>"text");
		$campos[] = array("name"=>"telefone","type"=>"text");
		$campos[] = array("name"=>"email","type"=>"text");
		$campos[] = array("name"=>"senha","type"=>"text");
		$campos[] = array("name"=>"idade","type"=>"integer");
		$campos[] = array("name"=>"saldo","type"=>"double precision");
		return $campos;
	}
	
	function getName(){
		return "pessoas";
	}
}


