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

        $userId = $this->getUserId($userFacebookUid);
        $followers = $this->getUserFollowers($userId) ;
        $news = $this->createNews("facebook" , $userId ) ;

        pushNewsIntoDatabase( $news , $userId , $followers ) ;

    }

    function getUserId($facebookUid) {
        //$request value
        //$data = $request->all()

        // firebase references
        $firebase = app('firebase') ;
        $users = $firebase->getDatabase()->getReference("/media") ;

        // get the user having this facebook id
        $snapshot = false ;
        $snapshot = $users
            ->orderBy("uid")
            ->equalTo($facebookUid)
            ->getSnapshot()
            ->getValue()
        ;

        $keys = array_keys($snapshot);

        return $keys[0] ;
    }
    function getUserFollowers($userId){
        $firebase = app('firebase') ;

        $followersRef = $firebase->getDatabase()->getReference("/followers") ;
        $followers = $followersRef.getChild($userId) ;

        return $followers->getChildKeys ();
    }
    function pushNewsIntoDatabase($news , $userId , $followers ) {

        // firebase references
        $firebase = app('firebase') ;
        $newsRef = $firebase->getDatabase()-> getReference("/news") ;
        $userNewsRef = $firebase->getDatabase()-> getReference("/user_news/".$userId) ;
        $feedRef = $firebase->getDatabase()-> getReference("/feed") ;

        $newsRef->set(['test' => "test"]) ;

        //get key of news
        $feedKey = $newsRef
            ->push()
            ->getKey() ;

        // Add news to news database
        $updates = [
            $feedKey => $news
        ] ;
        $newsRef->update($updates) ;

        // add news to user_news
        $userNewsRef->set([$feedKey => true]);

        //add news to followers feed
        foreach ($followers as $follower){
            $refFollower =  $feedRef->getChild($follower) ;
            $refFollower->update( [$feedKey => true] ) ;
        }

    }
    function createNews($socialMedia , $userId , $data) {

        $news = [
            "source" => "facebook" ,
            "uid"    => $userId
        ] ;

        return $news ;
    }

    function getUserTest( ) {
        $this->getUserId("10213440167502854") ;
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