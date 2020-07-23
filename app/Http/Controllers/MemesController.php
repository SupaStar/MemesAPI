<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Meme;
use Faker\Provider\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class MemesController extends Controller
{
    public function nuevo(Request $request)
    {
        //Se realiza el validador de la peticion
        $validator = Validator::make($request->all(),
            [
                "categoria" => "required",
                "meme" => "required",
                "titulo" => "required"
            ],
            [
                "categoria.required" => "Selecciona una categoria del meme."
            ]);
        //Si hay fallas se regresan con el mensaje
        if ($validator->fails()) {
            return response()->json(["estado" => false, "detalle" => $validator->errors()->all()]);
        }
        //Se crea el modelo meme
        $meme = new Meme();
        //Se asigna el idusuario con el que se recibe del request
        $meme->id_usuario = $request->input('id');
        //Se asigna la categoria
        $meme->id_categoria = $request->input('categoria');
        //Se asigna el titulo
        $meme->titulo = $request->input('titulo');
        //Se crea la variable imagen con la recibida
        $imagen = $request->input("meme");
        $imagen = str_replace('data:image/png;base64,', '', $imagen);
        $imagen = str_replace(' ', '+', $imagen);
        $imageName = mt_rand(1, 100) . $meme->id_categoria . $meme->id_usuario . $meme->id_categoria . '.' . 'png';
        Storage::disk('memes')->put($imageName, base64_decode($imagen));
        $meme->ruta = $imageName;
        $meme->save();
        return response()->json(["estado" => true]);
    }


    public function verTodos(Request $request)
    {
        if ($request->input('palabra') != null || $request->input('categoria') != null || $request->input('orden') != null) {
            if ($request->input('categoria') != null) {
                $categorias = Categoria::find($request->input('categoria'));
                return json_encode(["estado" => true, "memes" => $categorias->memes]);
            } else if ($request->input('orden') != null) {
                if ($request->input('orden') == 1) {
                    //Ordena de menor a mayor por fecha de creacion
                    $memes = Meme::orderBy('created_at', 'desc')->get();
                    return json_encode(["estado" => true, "memes" => $memes]);
                } else if ($request->input('orden') == 2) {
                    //Ordena de mayor a menor por fecha de creacion
                    $memes = Meme::orderBy('created_at', 'asc')->get();
                    return json_encode(["estado" => true, "memes" => $memes]);
                } else {
                    //Ordena de mayor a menor por puntuacion
                    $memes = Meme::all();
                    return json_encode(["estado" => true, "memes" => $memes->comentariosT]);
                }
            } else {
                $memes = Meme::where('titulo', $request->input('palabra'))
                    ->orWhere('titulo', 'like', '%' . $request->input('palabra') . '%')->get();
                return json_encode($memes);
            }
        } else {
            $memes = Meme::all();
            return json_encode($memes);
        }
    }

    public function comentariosM($id)
    {
        $meme = Meme::find($id);
        $comentarios = $meme->comentarios;
        $total = 0;
        foreach ($comentarios as $comentario) {
            $total += $comentario->puntuacion;
        }
        if ($total == 0) {
            $promedio = 0;
        } else {
            $promedio = $total / $comentarios->count();
        }
        return json_encode(["estado" => true, "comentarios" => $comentarios, "promedio" => $promedio]);

    }
}
