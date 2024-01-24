<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Providers\GoogleDriveAdapter;
use Illuminate\Support\Facades\Storage;
use google;
class GoogleDriverServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \Storage::extend('google', function ($app, $config) {
            $client = new \Google_Client();
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);
            
           
            //var_dump($config['refreshToken']);
            // Add the DRIVE scope for Google Drive
            $client->addScope(\Google_Service_Drive::DRIVE);
            // Fetch the access token explicitly
            //$accessToken = $client->fetchAccessTokenWithRefreshToken($config['refreshToken']);

            //$client->setAccessToken($accessToken);

            $service = new \Google_Service_Drive($client);
            //dd($service);
            dd($service);
            $adapter = new \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter($service, $config['folder']);
            //dd($adapter);
            return new \League\Flysystem\Filesystem($adapter);
        });
       
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}