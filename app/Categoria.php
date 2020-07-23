<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mpociot\Firebase\SyncsWithFirebase;

class Categoria extends Model
{
    use SyncsWithFirebase;
    protected $fillable = ['categoria'];
    public function memes(){
        return $this->hasMany("App\Meme","id_categoria","id");
    }
}
