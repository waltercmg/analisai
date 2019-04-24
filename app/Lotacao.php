<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lotacao extends Model
{
    protected $table = 'lotacoes';
    protected $fillable=['id', 'nome', 'lider_id'];
    
    public function lider()
    {
        return $this->hasOne('App\User', 'id', 'lider_id');
    }
    
    public function relatorios()
    {
        return $this->hasMany('App\Relatorio', 'lotacao_id' )->orderBy('id','desc');
    }
}
