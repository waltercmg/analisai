<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carga extends Model
{
    protected $table = 'cargas';
    protected $fillable=['id', 'usuario_id', 'data_extracao'];
    
    public function relatorios()
    {
        return $this->hasMany('App\Relatorio');
    }
    
    public function usuario()
    {
        return $this->hasOne('App\User', 'id', 'usuario_id');
    }
}
