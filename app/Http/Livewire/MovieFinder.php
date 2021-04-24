<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MovieFinder extends Component
{
    public $searchTerm;

    protected $queryString = [
        'searchTerm' => ['except' => ''],
    ];

    public function render()
    {
        return view('livewire.movie-finder');
    }
}
