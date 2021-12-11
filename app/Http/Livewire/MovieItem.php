<?php

namespace App\Http\Livewire;

use App\Movies;
use App\Notion\Client as Notion;
use Livewire\Component;
use Tmdb\Model\Movie;

class MovieItem extends Component
{
    protected $movie;

    public $added = false;

    public function mount(Movie $movie)
    {
        $this->movie = $movie;
    }

    public function render()
    {
        return view('livewire.movie-item')
            ->with('movie', $this->movie)
            ->with('editMode', $this->isInEditMode());
    }

    public function addToList($movieId)
    {
        $movies = app(Movies::class);
        $this->movie = $movies->loadById($movieId);

        if (! $this->notionHasMovieAlready($this->movie->getTitle())) {
            $this->addToNotion($this->movie);
        }
        $this->added = true;
    }

    protected function notionHasMovieAlready(string $movieName): bool
    {
        $filter = [
            'filter' => [
                'or' => [
                    [
                        'property' => 'Name',
                        'text' => [
                            'equals' => $movieName,
                        ],
                    ],
                ],
            ],
        ];

        $notion = app(Notion::class);
        $results = $notion->queryDatabase($filter);

        return count($results) > 0;
    }

    protected function addToNotion(Movie $movie): void
    {
        $notion = app(Notion::class);

        $databaseEntry = [
            'Name' => [
                'title' => [
                    [
                        'text' => [
                            'content' => $movie->getTitle(),
                        ],
                    ],
                ],
            ],
            'IMDB' => [
                'rich_text' => [
                    [
                        'text' => [
                            'content' => $movie->getExternalIds()->getImdbId() ?? '',
                        ],
                    ],
                ],
            ],
            'Release' => [
                'rich_text' => [
                    [
                        'text' => [
                            'content' => \Carbon\Carbon::make($movie->getReleaseDate())?->toDateString() ?? 'announced',
                        ],
                    ],
                ],
            ],
            'Added By' => [
                'select' => [
                    'name' => 'Movie-Finder',
                ],
            ],
            'Links' => [
                'url' => $movie->getExternalIds()->getImdbId() ? "https://hd-source.to/?s={$movie->getExternalIds()->getImdbId()}" : '',
            ],
        ];

        $notion->addEntryToDatabase($databaseEntry);
    }

    protected function isInEditMode()
    {
        return session()->get('editModeEnabled', false);
    }
}
