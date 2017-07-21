<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Factory as FirebaseFactory;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton(Firebase::class, function () {

            $serviceAccount = ServiceAccount::fromJsonFile( "/bump-81ddd-firebase-adminsdk-mb87d-3cf375a0a5.json") ;

            return (new FirebaseFactory())
                ->withServiceAccount($serviceAccount)
                ->withDatabaseUri('https://bump-81ddd.firebaseio.com/
')
                ->create();
        });

        $this->app->alias(Firebase::class, 'firebase');
    }
}