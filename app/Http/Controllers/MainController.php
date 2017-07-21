<?php

namespace App\Http\Controllers;

use App\Providers\FirebaseServiceProvider;
use Illuminate\Http\Request;
use Kreait\Firebase ;

class MainController extends Controller
{
    public function facebookReceive(Request $request ) {

        // firebase references
        $firebase = app('firebase') ;
        $users = $firebase->getDatabase()->getReference("/users") ;

        //$request value
        $data = $request->all() ;
/*
        foreach( $users as $user )  {
            if ( !isset($user->account["facebook"] )  ) {
                if (  $user["account"]["facebook"]["uid"] == $data["entry"][0]["uid"]) {
                    // add to follower feed the news

                }
            }
        }
*/
        $ref = $firebase->getDatabase()->getReference("/testPost/" . rand(1, 500)) ;
        $ref->update($request->input("entry"))   ;
        //$ref->update($data["entry"][0]->uid )   ;
        //$ref->update($data["entry"][0].uid )   ;

        // get the current user id
        //
        var_dump($data["entry"] ) ;
       // die() ;

    }
}
