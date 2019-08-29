<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chamado extends Model
{
    use SoftDeletes;

    protected $table = 'Chamados';
    protected $primaryKey = 'id';

    public function usuario()
    {
        return $this->belongsTo('App\Model\Usuario', 'Usuarios_id');
    }
}