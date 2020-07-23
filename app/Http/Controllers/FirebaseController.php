<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;

class FirebaseController extends Controller
{
    public function index()
    {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__ . '/appmemes-7537f-firebase-adminsdk-5ocs7-965c62aa60.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://appmemes-7537f.firebaseio.com/')
            ->create();
        $database = $firebase->getDatabase();
        $newPost = $database
            ->getReference('blog/posts')
            ->push([
                'title' => 'Post title',
                'body' => 'This should probably be longer.'
            ]);
        echo "<pre>";
        print_r($newPost->getvalue());
    }

    public static function nueva($data, $tipo)
    {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__ . '/appmemes-7537f-firebase-adminsdk-5ocs7-965c62aa60.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://appmemes-7537f.firebaseio.com/')
            ->create();
        $database = $firebase->getDatabase();
        $newPost = $database
            ->getReference('/' . $tipo)
            ->push($data);
        return response()->json(["estado"=>true]);
    }
}
