<?php

namespace App;

use Tmdb\Model\Movie;
use Tmdb\Model\Movie\QueryParameter\AppendToResponse;
use Tmdb\Repository\MovieRepository;

class Movies
{
    public function __construct(protected MovieRepository $movies)
    {
    }

    public function loadById($movieId): Movie
    {
        return $this->movies->load($movieId, $this->getTmdbQueryParams());
    }

    public function load(Movie $movie): Movie
    {
        return $this->loadById($movie->getId());
    }

    protected function getTmdbQueryParams($lang = null)
    {
        $parameters = [
            new AppendToResponse([
                AppendToResponse::ALTERNATIVE_TITLES,
                AppendToResponse::CHANGES,
                AppendToResponse::CREDITS,
                AppendToResponse::IMAGES,
                AppendToResponse::KEYWORDS,
                //AppendToResponse::LISTS,
                AppendToResponse::RELEASE_DATES,
                AppendToResponse::REVIEWS,
                AppendToResponse::SIMILAR,
                AppendToResponse::TRANSLATIONS,
                AppendToResponse::VIDEOS,
            ]),
        ];

        if ($lang != null) {
            $parameters['language'] = $lang;
        }

        return $parameters;
    }
}
