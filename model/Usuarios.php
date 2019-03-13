<?php

/**
 *
 * @return Usuarios
 */
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
 * @property Tightenco\Collect\Support\Collection $permissoes Description
 */
class Usuarios extends Model {
    function allow($permission_name) {
        if(is_array($permission_name)){
            return $this->permissoes->whereIn('nome',$permission_name)->count() > 0;
        }else{
            return $this->permissoes->where('nome',$permission_name)->count() > 0;
        }
        
    }

    public function on_get(&$field, &$value) {
        if($field === 'permissoes'){
            $value = model_permissoes()->where('id',model_usuarios_permissoes()->where('usuario', $this->id)->get()->pluck('permissao'))->get();
        }
        parent::on_get($field, $value);
    }

}
