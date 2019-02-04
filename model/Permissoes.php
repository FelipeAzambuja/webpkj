<?php

/**
 *
 * @return Permissoes     */
function model_permissoes() {
    return new Permissoes();
}

/**
 * @table permissoes
 * @property integer $id Gerado pelo pkj
 * @property string $nome Gerado pelo pkj
 * @property datetime $momento Gerado pelo pkj
 * @property Usuarios $usuarios Description
 */
class Permissoes extends Model {

    public function on_get(&$field, &$value) {
        if($field === 'usuarios'){
            return model_usuarios()->where('id',model_usuarios_permissoes()->where('permissao',$this->id)->get()->pluck('usuario'))->get();
        }
        parent::on_get($field, $value);
    }
}
