<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tmdb\Client;
use Tmdb\Token\Api\ApiToken;

class TmdbServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Tmdb\Adapters\EventDispatcherAdapter::class, \App\Tmdb\Adapters\EventDispatcherLaravel::class);

        // Let the IoC container be able to make a Symfony event dispatcher
        $this->app->bind(
            EventDispatcherInterface::class,
            EventDispatcher::class,
        );

        // Setup default configurations for the Tmdb Client
        $this->app->singleton(\Tmdb\Client::class, function () {
            $config = config('tmdb');
            $options = $config['options'];

            // Use an Event Dispatcher that uses the Laravel event dispatcher
            $options['event_dispatcher'] = $this->app->make(\App\Tmdb\Adapters\EventDispatcherAdapter::class);

            // Register the client using the key and options from config
            $token = new ApiToken($config['api_key']);

            return new Client($token, $options);
        });

        // bind the configuration (used by the image helper)
        $this->app->bind(\Tmdb\Model\Configuration::class, function () {
            $configuration = $this->app->make(\Tmdb\Repository\ConfigurationRepository::class);

            return $configuration->load();
        });
    }
}
