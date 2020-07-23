<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mpociot\Firebase\SyncsWithFirebase;

class Meme extends Model
{
    use SyncsWithFirebase;
    protected $fillable = ['id_usuario', 'id_categoria', 'titulo', 'descripcion', 'ruta'];

    public function comentarios()
    {
        return $this->hasMany('App\ComentarioMeme', 'id_meme', 'id');
    }

    public function comentariosT()
    {
        return $this->belongsTo("App\ComentarioMeme", "id_meme", "id");
    }
}
