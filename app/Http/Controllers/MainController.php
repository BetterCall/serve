<?php

namespace App\Http\Controllers;

use App\Providers\FirebaseServiceProvider;
use Illuminate\Http\Request;
use Kreait\Firebase ;

class MainController extends Controller
{
    public function facebookReceive(Request $request ) {

        //$request value
        $data = $request->all() ;
        $userFacebookUid = $data["entry"][0]["id"] ;

        // firebase references
        $firebase = app('firebase') ;
        $users = $firebase->getDatabase()->getReference("/users") ;


/*
        foreach( $users as $user )  {
            if ( !isset($user->account["facebook"] )  ) {
                if (  $user["account"]["facebook"]["uid"] == $data["entry"][0]["uid"]) {
                    // add to follower feed the news

                }
            }
        }
*/
        //$ref = $firebase->getDatabase()->getReference("/testPost/" . ) ;
        //var $test = json_decode($request->getContent(), true);

        //$ref->update( $data ) ;
        $ref->set($data["entry"][0]["changes"])   ;
        //$ref->set($data["entry"][0]["changes"])   ;
        //$ref->update($data)   ;

        // get the current user id
        //
        //dd(json_decode($request->getContent(), true));

        //var_dump($data["entry"] ) ;
       // die() ;

    }

    function getUser() {
        $firebase = app('firebase') ;
        $users = $firebase->getDatabase()->getReference("/users") ;

        $firebase.startAt("o").endAt('0').once("value" , function($snap) {
            var_dump($snap) ;
        });
    }
}
