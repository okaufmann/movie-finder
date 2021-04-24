<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Tmdb\Model\Search\SearchQuery\MovieSearchQuery;
use Tmdb\Repository\SearchRepository;

class MovieFinder extends Component
{
    public $searchTerm;

    protected $queryString = [
        'searchTerm' => ['except' => ''],
    ];

    public function render()
    {
        $movies = [];
        if ($this->searchTerm) {
            $movies = $this->findMovie();
        }

        return view('livewire.movie-finder')->with('movies', $movies);
    }

    protected function findMovie()
    {
        $search = app(SearchRepository::class);
        $query = new MovieSearchQuery();
        $query->page(1);

        $find = $search->searchMovie($this->searchTerm, $query);

        return array_values($find->getAll());
    }
}
