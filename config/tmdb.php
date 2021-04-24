<?php

return [
    /*
     * Api key
     */
    'api_key' => env('TMDB_API_KEY'),

    /*
     * Cache
     */
    'cache' => [
        'enabled' => true,
        // Keep the path empty or remove it entirely to default to storage/tmdb
        'path' => storage_path('tmdb'),
    ],

    /*
     * Client options
     */
    'options' => [
        /*
         * Use https
         */
        'secure' => true,

        //
        // /*
        //  * Log
        //  */
        // 'log' => [
        //     'enabled' => true,
        //     // Keep the path empty or remove it entirely to default to storage/logs/tmdb.log
        //     'path' => storage_path('logs/tmdb.log'),
        // ],
    ],
];
