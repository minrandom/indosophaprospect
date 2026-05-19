<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google_Client;
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;

use League\Flysystem\Filesystem;
use Storage;
class GoogleDriverServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('google', function ($app, $config) {
            $client = new \Google_Client();
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);

            $service = new \Google_Service_Drive($client);

            $options = [];

            if (isset($config['teamDriveId'])) {
                $options['teamDriveId'] = $config['teamDriveId'];
            }

            $folder = $config['folder'] ?? null;

            if (!$folder && isset($config['folders']['default'])) {
                $folder = $config['folders']['default'];
            }

            if (!$folder) {
                throw new \Exception('Google Drive folder is not configured.');
            }

            $adapter = new GoogleDriveAdapter($service, $folder, $options);

            return new Filesystem($adapter);
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
