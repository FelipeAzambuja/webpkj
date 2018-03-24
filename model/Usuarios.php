<?php

/**
 * @table usuarios
 * @autoload UsuarioContatos
 * @property integer $id Gerado pelo pkj
 * @property string $nome Gerado pelo pkj
 * @property blob $imagem Gerado pelo pkj
 * @property string $nivel Gerado pelo pkj
 * @property string $frase Gerado pelo pkj
 * @property string $senha Gerado pelo pkj
 * @property string $email Gerado pelo pkj
 * @property string $lembrar Gerado pelo pkj
 * @property datetime $alterado Gerado pelo pkj
 * @property UsuarioContatos $contatos id=usuarios
 */
class Usuarios extends Model {
    
}
