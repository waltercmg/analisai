<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relatorio extends Model
{
    protected $table = 'relatorios';
    protected $fillable=['id', 'lotacao_id', 'carga_id', 'analise', 'acoes', 'conteudo'];
    
    public function lotacao()
    {
        return $this->hasOne('App\Lotacao', 'id', 'lotacao_id');
    }
    
    public function carga()
    {
        return $this->hasOne('App\Carga', 'id', 'carga_id');
    }
}
