<?php

namespace App\Http\Controllers;

use App\Providers\FirebaseServiceProvider;
use Illuminate\Http\Request;
use Kreait\Firebase ;
use Kreait\Firebase\Database\Query ;


class MainController extends Controller
{
    public function facebookReceive(Request $request ) {

        //$request value
        $data = $request->all() ;
        $userFacebookUid = $data["entry"][0]["id"] ;

        // firebase references
        $firebase = app('firebase') ;
        $users = $firebase->getDatabase()->getReference("/users") ;

        // get the user having this facebook id
        $snapshot = false ;
        $snapshot = $users
            ->orderByChild("account/facebook/uid")
            ->equalTo($userFacebookUid)
            ->getSnapshot()
            ->getValue()
        ;
        $keys = array_keys($snapshot);

        $followers = array_keys($snapshot[$keys[0]]["followers"]) ;
        foreach ($followers as $follower) {

            $refFollower =  $users->getReference("/".$follower."/feeds") ;
            $feedKey = $refFollower
                        ->push()
                        ->getKey() ;
            $feedData = $data["entry"][0]["changes"] ;

            $updates = [
                $feedKey => $feedData
            ] ;
            $refFollower->update($updates) ;

        }
        //$ref->set($data["entry"][0]["changes"])   ;
        //$ref->set($data["entry"][0]["changes"])   ;
        //$ref->update($data)   ;



    }

    function getUser() {
        $firebase = app('firebase') ;
        $users = $firebase->getDatabase()->getReference("/users") ;





        //$followers ;

        //var_dump($snapshot);
        //var_dump($keys);




        dd();
        die() ;
        //dd($snapshot[0]) ;
        //die() ;


    }
}
