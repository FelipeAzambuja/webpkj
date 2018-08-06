<?php

/**
 *
 * @return Usuarios     */
function model_usuarios() {
    return new Usuarios();
}

/**
 * @table usuarios
 * @property integer $id Gerado pelo pkj
 * @property string $nome Gerado pelo pkj
 * @property image $imagem Gerado pelo pkj
 * @property string $nivel Gerado pelo pkj
 * @property string $frase Gerado pelo pkj
 * @property string $senha Gerado pelo pkj
 * @property string $email Gerado pelo pkj
 * @property string $lembrar Gerado pelo pkj
 * @property datetime $alterado Gerado pelo pkj
 */
class Usuarios extends Model {


}
