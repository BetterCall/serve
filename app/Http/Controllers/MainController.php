<?php

namespace App\Http\Controllers;

use App\Providers\FirebaseServiceProvider;
use Illuminate\Http\Request;
use Kreait\Firebase ;
use Kreait\Firebase\Database\Query ;


class MainController extends Controller
{
    public $lastReq  ;
    public $user = "" ;

    public function facebookReceive(Request $request ) {
        //$request value
        $data = $request->all() ;
        $userFacebookUid = $data["entry"][0]["id"] ;



        $this->getUserId($userFacebookUid);






    }

    function getUserId($facebookUid) {
        //$request value
        //$data = $request->all()



        // firebase references
        $firebase = app('firebase') ;
        $social = $firebase->getDatabase()->getReference("/social/facebook") ;
        $users = $firebase->getDatabase()->getReference("/media") ;

        // get the user having this facebook id
        $snapshot = false ;
        $snapshot = $social
            -> getChild($facebookUid)
            ->getSnapshot()
            ->getValue()
            ;

        /*
         * $snapshot = $users
            -> getChild( )
            ->orderByChild("uid")
            ->equalTo($facebookUid)
            ->getSnapshot()
            ->getValue()
        ;
        */

        $keys = array_keys($snapshot);

        $this->getUserFollowers($keys[0]) ;
    }
    function getUserFollowers($userId){
        $firebase = app('firebase') ;

        $followersRef = $firebase->getDatabase()->getReference("/followers") ;
        $followers = $followersRef->getChild($userId) ;


        $this->createNews("facebook" , $userId , $followers->getChildKeys ()  ) ;

    }
    function pushNewsIntoDatabase($news , $userId , $followers ) {

        // firebase references
        $firebase = app('firebase') ;
        $newsRef = $firebase->getDatabase()-> getReference("/news") ;
        $userNewsRef = $firebase->getDatabase()-> getReference("/user_news/".$userId) ;
        $feedRef = $firebase->getDatabase()-> getReference("/feed") ;

        //get key of news
        $feedKey = $newsRef
            ->push()
            ->getKey() ;

        // Add news to news database
        $updates = [
            $feedKey => $this->lastReq
        ] ;
        $newsRef->update($updates) ;

        // add news to user_news
        $userNewsRef->update([$feedKey => true]);

        //add news to followers feed
        foreach ($followers as $follower){
            $refFollower =  $feedRef->getChild($follower) ;
            $refFollower->update( [$feedKey => true] ) ;
        }

        return http_response_code(200) ;
        //return response("", 200) ;

    }
    function createNews($socialMedia , $userId ,$followers ) {

        $news = [
            "source" => "facebook" ,
            "uid"    => $userId
        ] ;

        return $this->pushNewsIntoDatabase($news , $userId , $followers ) ;
    }

    function getUserTest( ) {
        var_dump( $this->user );
        $this->getUserId(   $this->user  ) ;
    }

}


/*
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
        */