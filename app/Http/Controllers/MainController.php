<?php

namespace App\Http\Controllers;

use App\Providers\FirebaseServiceProvider;
use Illuminate\Http\Request;
use Kreait\Firebase

class MainController extends Controller
{
    public function facebookReceive(Request $request ) {

        $data = $request->all() ;

        $firebase = app('firebase') ;
        $firebase->getDatabase()->getReference("/users") ;


        // get the current user id
        $id = $data["entry"] ;
        dd($id) ;
        die() ;

    }
}
