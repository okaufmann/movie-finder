@php /** @var $movie \Tmdb\Model\Movie */ @endphp
@inject('imageHelper', \Tmdb\Helper\ImageHelper::class)
<div class="flex flex-col sm:flex-row">
    <div class=" sm:min-w-[150px] flex justify-center">
        @if($movie->getPosterImage())
            {!!  $imageHelper->getHtml($movie->getPosterImage(), 'w185', 150, 75) !!}
        @endif
    </div>
    <div class="flex flex-col justify-between w-full p-4">
        <div class="flex flex-col space-y-1 ">
            <div class="text-xl font-semibold">
                <a href="https://themoviedb.org/movie/{{ $movie->getId() }}">{{ $movie->getTitle() }} </a>({{ \Carbon\Carbon::make($movie->getReleaseDate())->toDateString() }}
                )
            </div>
            <div>
                <p>
                    {{ \Illuminate\Support\Str::limit($movie->getOverview(), 200) }}
                </p>
            </div>
            <div class="italic">
                @php
                    use Tmdb\Model\Person\CastMember;
                    $castList = collect($movie->getCredits()->getCast()->getAll())->take(5);
                @endphp
                @foreach($castList as $cast)
                    <a class="text-blue-800"
                       href="https://www.themoviedb.org/person/{{$cast->getId() }}">{{$cast->getName()}}
                        ({{ $cast->getCharacter() }})</a>
                @endforeach
            </div>
        </div>
        <div class="flex justify-between w-full pt-4">
            <a href="https://imdb.com/title/{{ $movie->getExternalIds()->getImdbId() }}"
               class="text-blue-800">{{ $movie->getExternalIds()->getImdbId() }}</a>

            @if($editMode)
                <div wire:loading>
                    Adding...
                </div>

                <div wire:loading.remove>
                @if($added)
                    <span class="text-blue-800">Added!</span>
                @else
                    <button class="px-2 py-1 text-white bg-blue-800 rounded"
                            wire:click="addToList('{{ $movie->getId() }}')">Add to List
                    </button>
                @endif
                </div>
            @endif
        </div>
    </div>

</div>
