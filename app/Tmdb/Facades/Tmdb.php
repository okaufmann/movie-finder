<?php

namespace App\Tmdb\Facades;

use Illuminate\Support\Facades\Facade;

class Tmdb extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Tmdb\Client::class;
    }
}
