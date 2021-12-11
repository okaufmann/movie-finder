<?php

namespace App\Http\Livewire;

use App\Movies;
use Livewire\Component;
use Tmdb\Model\Movie;
use Tmdb\Model\Search\SearchQuery\MovieSearchQuery;
use Tmdb\Repository\SearchRepository;

class MovieFinder extends Component
{
    public $searchTerm;

    public $showMoviesWithoutImdb = false;

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'showMoviesWithoutImdb',
    ];

    public function mount()
    {
        $this->setEditMode();
    }

    public function render()
    {
        $movies = [];
        if ($this->searchTerm) {
            $movies = $this->findMovie();
        }

        return view('livewire.movie-finder')->with('movies', $movies);
    }

    /**
     * @return Movie[]
     */
    protected function findMovie(): array
    {
        $search = app(SearchRepository::class);
        $movies = app(Movies::class);
        $query = new MovieSearchQuery();
        $query->page(1);
        $find = $search->searchMovie($this->searchTerm, $query);
        $result = array_values($find->getAll());

        $moviesWithData = collect();
        foreach ($result as $movie) {
            $moviesWithData[] = $movies->load($movie);
        }

        if (! $this->showMoviesWithoutImdb) {
            $moviesWithData = $moviesWithData->filter(function (Movie $movie) {
                return $movie->getImdbId();
            });
        }

        $moviesWithData = $moviesWithData->values()->sortByDesc([
            function (Movie $a, Movie $b) {
                if (is_string($b->getReleaseDate()) && empty(trim($b->getReleaseDate()))) {
                    return 0;
                }

                if (is_string($a->getReleaseDate()) && empty(trim($a->getReleaseDate()))) {
                    return 0;
                }

                // https://www.php.net/manual/en/language.operators.comparison.php
                return $b->getReleaseDate()?->getTimestamp() <=> $a->getReleaseDate()?->getTimestamp();
            },
        ]);

        return $moviesWithData->toArray();
    }

    protected function setEditMode()
    {
        if (request()->query('m') === md5('edit')) {
            session()->put('editModeEnabled', true);
        }
    }
}
