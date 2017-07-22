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
        //var $test = json_decode($request->getContent(), true);

        //$ref->update( $data ) ;
        $ref->update($data["entry"][0])   ;
        //$ref->update($data)   ;

        // get the current user id
        //
        //dd(json_decode($request->getContent(), true));

        //var_dump($data["entry"] ) ;
       // die() ;

    }
}
