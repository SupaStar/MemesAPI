<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//Ruta para crear usuario
Route::post("nuevoUsuario","UsuariosController@nuevo");
//Login
Route::post("login","UsuariosController@login");
//Subir meme
Route::post("nuevoMeme","MemesController@nuevo");
//Publicar comentario de un meme
Route::post("nuevocomentario","ComentariosController@nuevo");
//Ver un usuario
Route::get("verUsuario/{id}","UsuariosController@verUsuario");
//Ver memes de un usuario
Route::get("verMemesUsuario/{id}","UsuariosController@verUsuario");
//Ver todos los memes
Route::post("verMemes","MemesController@verTodos");
//Ver comentarios de un meme
Route::get("verComentarios/{id}","MemesController@comentariosM");
