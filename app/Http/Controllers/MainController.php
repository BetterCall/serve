<?php

namespace App\Http\Controllers;

use App\Providers\FirebaseServiceProvider;
use Illuminate\Http\Request;
use Kreait\Firebase ;

class MainController extends Controller
{
    public function facebookReceive(Request $request ) {

        $data = $request->all() ;

        $firebase = app('firebase') ;
        $ref = $firebase->getDatabase()->getReference("/testPost/" . rand(1, 500)) ;
        $ref->update($data["entry"][0]["id"])   ;

        // get the current user id
        //
        dd($data ) ;
       // die() ;

    }
}
