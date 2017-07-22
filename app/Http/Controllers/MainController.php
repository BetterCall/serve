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
        // get database user id
        $keys = array_keys($snapshot);

        $followers = array_keys($snapshot[$keys[0]]["followers"]) ;
        foreach ($followers as $follower) {

            $refFollower =  $users->getChild($follower."/feeds") ;
            $refFollower->update(["test" => "test"] ) ;

            $feedKey = $refFollower
                ->push()
                ->getKey() ;
            $feedData = ["test" => "test"] ; //$data["entry"][0]["changes"] ;

            $updates = [
                $feedKey => $feedData
            ] ;
            $refFollower->update($updates) ;

        }

    }

    function getUser() {
        //$request value
        //$data = $request->all() ;
        $userFacebookUid = 0 ;

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
        echo "<pre>" ;
        var_dump($snapshot);
        echo "</pre>" ;

        $keys = array_keys($snapshot);

        echo "<pre>" ;
        var_dump($keys);
        echo "</pre>" ;


        $followers = array_keys($snapshot[$keys[0]]["followers"]) ;
        echo "<pre>" ;
        var_dump($followers);
        echo "</pre>" ;

        foreach ($followers as $follower) {

            $refFollower =  $users->getChild($follower."/feeds") ;
            $refFollower->update(["test" => "test"] ) ;

            $feedKey = $refFollower
                        ->push()
                        ->getKey() ;
            $feedData = ["test" => "test"] ; //$data["entry"][0]["changes"] ;

            $updates = [
                $feedKey => $feedData
            ] ;
            $refFollower->update($updates) ;

        }
    }
}
