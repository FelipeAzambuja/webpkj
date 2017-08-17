<?php
/**
 * 
 * @return	Usuarios
 */
function orm_usuarios(){
	return new Usuarios();
}
class Usuarios extends DBTable {
	
	
	function getFields(){
		$campos = [];
		return $campos;
	}
	
	function getName(){
		return "usuarios";
	}
}


