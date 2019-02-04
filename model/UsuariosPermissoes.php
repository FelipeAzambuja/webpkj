<?php

/**
 *
 * @return UsuariosPermissoes     */
function model_usuarios_permissoes() {
    return new UsuariosPermissoes();
}

/**
 * @table usuarios_permissoes
 * @property integer $id Gerado pelo pkj
 * @property integer $usuario Gerado pelo pkj
 * @property integer $permissao Gerado pelo pkj
 * @property datetime $momento Gerado pelo pkj
 */
class UsuariosPermissoes extends Model {
    
}
