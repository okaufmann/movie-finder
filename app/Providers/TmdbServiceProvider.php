<?php

namespace App\Providers;

use App\Tmdb\Adapters\EventDispatcherAdapter;
use App\Tmdb\Adapters\EventDispatcherLaravel;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tmdb\Client;
use Tmdb\Event\BeforeRequestEvent;
use Tmdb\Event\Listener\Psr6CachedRequestListener;
use Tmdb\Event\Listener\Request\AcceptJsonRequestListener;
use Tmdb\Event\Listener\Request\ApiTokenRequestListener;
use Tmdb\Event\Listener\Request\ContentTypeJsonRequestListener;
use Tmdb\Event\Listener\Request\UserAgentRequestListener;
use Tmdb\Event\RequestEvent;
use Tmdb\Model\Configuration;
use Tmdb\Repository\ConfigurationRepository;
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
        $this->app->bind(EventDispatcherAdapter::class, EventDispatcherLaravel::class);

        // Let the IoC container be able to make a Symfony event dispatcher
        $this->app->bind(
            EventDispatcherInterface::class,
            EventDispatcher::class,
        );

        // Setup default configurations for the Tmdb Client
        $this->app->singleton(Client::class, function () {
            $config = config('tmdb');
            $options = $config['options'];

            // Use an Event Dispatcher that uses the Laravel event dispatcher
            $ed = $this->app->make(EventDispatcherAdapter::class);
            $options['event_dispatcher']['adapter'] = $ed;

            // Register the client using the key and options from config
            $token = new ApiToken($config['api_key']);
            $options['api_token'] = $token;

            $client = new Client($options);

            /**
             * Required event listeners and events to be registered with the PSR-14 Event Dispatcher.
             */
            /**
             * Instantiate the PSR-6 cache.
             */
            $cache = new FilesystemAdapter('php-tmdb', 86400, $config['cache']['path']);

            /**
             * The full setup makes use of the Psr6CachedRequestListener.
             *
             * Required event listeners and events to be registered with the PSR-14 Event Dispatcher.
             */
            $requestListener = new Psr6CachedRequestListener(
                $client->getHttpClient(),
                $ed,
                $cache,
                $client->getHttpClient()->getPsr17StreamFactory(),
                []
            );
            $ed->addListener(RequestEvent::class, $requestListener);

            $apiTokenListener = new ApiTokenRequestListener($client->getToken());
            $ed->addListener(BeforeRequestEvent::class, $apiTokenListener);

            $acceptJsonListener = new AcceptJsonRequestListener();
            $ed->addListener(BeforeRequestEvent::class, $acceptJsonListener);

            $jsonContentTypeListener = new ContentTypeJsonRequestListener();
            $ed->addListener(BeforeRequestEvent::class, $jsonContentTypeListener);

            $userAgentListener = new UserAgentRequestListener();
            $ed->addListener(BeforeRequestEvent::class, $userAgentListener);

            return $client;
        });

        // bind the configuration (used by the image helper)
        $this->app->bind(Configuration::class, function () {
            $configuration = $this->app->make(ConfigurationRepository::class);

            return $configuration->load();
        });
    }
}
