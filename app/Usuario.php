<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\App;
class Usuario extends Model
{
    protected $table = 'usuarios';
    public function memes(){
        return $this->hasMany("App\Meme","id_usuario","id");
    }
}
