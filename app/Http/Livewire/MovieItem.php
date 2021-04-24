<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Tmdb\Model\Movie;

class MovieItem extends Component
{
    protected $movie;

    public function mount(Movie $movie)
    {
        $this->movie = $movie;
    }

    public function render()
    {
        return view('livewire.movie-item')->with('movie', $this->movie);
    }
}
