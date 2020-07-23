<?php

namespace App\Http\Controllers;

use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "username" => "required",
            "mail" => "required",
            "password" => "required|min:6",
            "rpassword" => "required|same:password"
        ], [
            "username.required" => "El nombre de usuario es requerido.",
            "mail.required" => "El email es requerido.",
            "password.required" => "La contraseña es requerida.",
            "password.min" => "La contraseña debe de ser al menos 6 caracteres.",
            "rpassword.required" => "La confirmacion de la contraseña es requerida.",
            "rpassword.same" => "Las contraseñas no coinciden."
        ]);
        if ($validator->fails()) {
            return response()->json(["estado" => false, "detalle" => $validator->errors()->all()]);
        }
        $usuario = new Usuario();
        $usuario->username = $request->input('username');
        $usuario->mail = $request->input('mail');
        $usuario->password = Hash::make($request->input('password'));
        if (Usuario::where('username', $request->input('username'))->first() == null) {
            $usuario->save();
            return response()->json(["estado" => true]);
        } else {
            return response()->json(["estado" => false, "detalle" => "Nombre de usuario en uso"]);
        }
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            "username" => "required",
            "password" => "required|min:6",
        ], [
            "username.required" => "Falta el campo nombre de usuario.",
            "password.required" => "Falta el campo contraseña.",
            "password.min" => "La contraseña debe ser de al menos 6 carácteres."
        ]);
        if ($validator->fails()) {
            return response()->json(["estado" => false, "detalle" => $validator->errors()->all()]);
        }
        $usuario = Usuario::where('username', $request->input('username'))->first();
        if ($usuario != null){
            if (Hash::check($request->input('password'), $usuario->password)) {
                $id = $usuario->id;
                return json_encode(["estado" => true, "id" => $id]);
            }
        }
        $error=["Nombre de usuario o password incorrectos"];
        return json_encode(["estado" => false, "detalle" => $error]);
    }
    public function verUsuario($id){
        $usuario=Usuario::find($id);
        return response()->json($usuario);
    }
    public function verMemesUsuario($id){
        $usuario=Usuario::find($id);
        return response()->json($usuario->memes);
    }
}
