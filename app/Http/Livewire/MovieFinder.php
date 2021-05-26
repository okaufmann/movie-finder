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

    protected $queryString = [
        'searchTerm' => ['except' => ''],
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

    protected function findMovie()
    {
        $search = app(SearchRepository::class);
        $movies = app(Movies::class);
        $query = new MovieSearchQuery();
        $query->page(1);
        $find = $search->searchMovie($this->searchTerm, $query);
        $result = array_values($find->getAll());

        $moviesWithData = [];
        foreach ($result as $movie) {
            $moviesWithData[] = $movies->load($movie);
        }

        $moviesWithData = collect($moviesWithData)->values()->sortByDesc([
            fn (Movie $a, Movie $b) => $b->getReleaseDate()?->getTimestamp() <=> $a->getReleaseDate()?->getTimestamp(),
        ]);

        return $moviesWithData;
    }

    protected function setEditMode()
    {
        if (request()->query('m') === md5('edit')) {
            session()->put('editModeEnabled', true);
        }
    }
}
