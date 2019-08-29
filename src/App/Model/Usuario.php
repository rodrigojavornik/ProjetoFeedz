<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


class Usuario extends Model
{
    protected $table = 'Usuarios';
    protected $primaryKey = 'id';

    public function chamado()
    {
        $this->hasMany(Chamado::class);
    }
}