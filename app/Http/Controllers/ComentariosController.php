<?php

namespace App\Http\Controllers;

use App\ComentarioMeme;
use App\TablaComentarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComentariosController extends Controller
{

    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "puntuacion" => "required"
        ], [
            "puntuacion.required" => "Dale una puntuacion a este meme."
        ]);
        if ($validator->fails()) {
            return response()->json(["estado" => false, "detalle" => $validator->errors()->all()]);
        }
        $comentario = new ComentarioMeme();
        $comentario->id_usuario = $request->input('id');
        $comentario->id_meme = $request->input('idmeme');
        $comentario->puntuacion = $request->input('puntuacion');
        $comentario->comentario = $request->input('comentario');
        $comenta = ComentarioMeme::where('id_usuario', $request->input('id'))->first();
        if ($comenta != null) {
            if (ComentarioMeme::where('id_meme', $request->input('idmeme'))->first() != null) {
                return response()->json(["estado" => false, "detalle" => "Ya se hizo un comentario de este meme."]);
            } else if ($comenta->id_meme != $request->input('idmeme')) {
                $comentario->save();
                return response()->json(["estado" => true]);
            }
        }
        $comentario->save();
        return response()->json(["estado" => true]);
    }
}
