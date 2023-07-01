<?php

namespace App\Tmdb\Adapters;

use Illuminate\Contracts\Events\Dispatcher as LaravelDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyDispatcher;

/**
 * This adapter provides a Laravel integration for applications
 * using the Symfony EventDispatcherInterface
 * It passes any request on to a Symfony Dispatcher and only
 * uses the Laravel Dispatcher only when dispatching events.
 */
class EventDispatcherLaravel extends EventDispatcherAdapter
{
    /**
     * Forward all methods to the Laravel Events Dispatcher.
     */
    public function __construct(LaravelDispatcher $laravelDispatcher, SymfonyDispatcher $symfonyDispatcher)
    {
        $this->laravelDispatcher = $laravelDispatcher;
        $this->symfonyDispatcher = $symfonyDispatcher;
    }
}
