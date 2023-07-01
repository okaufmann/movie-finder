@php /** @var $movie \Tmdb\Model\Movie */ @endphp
@inject('imageHelper', \Tmdb\Helper\ImageHelper::class)
<div class="flex flex-col sm:flex-row" x-data="{
    copy(text) {
        console.log(text)
        navigator.clipboard.writeText(text)
    }
}">
    <div class=" sm:min-w-[150px] flex justify-center">
        @if($movie->getPosterImage())
            {!!  $imageHelper->getHtml($movie->getPosterImage(), 'w185', 150, 75) !!}
        @endif
    </div>
    <div class="flex flex-col justify-between w-full p-4">
        <div class="flex flex-col space-y-1 ">
            <div class="text-xl font-semibold">
                <a href="https://themoviedb.org/movie/{{ $movie->getId() }}">{{ $movie->getTitle() }} </a>
                @if($movie->getReleaseDate())
                    ({{ \Carbon\Carbon::make($movie->getReleaseDate())?->toDateString() }})
                @endif
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
                        ({{ $cast->getCharacter() }})</a>@if(!$loop->last), @endif
                @endforeach
            </div>
        </div>
        <div class="flex justify-between w-full pt-4">
            <div class="flex space-x-4">
                @if( $movie->getImdbId())
                    <a href="https://imdb.com/title/{{ $movie->getExternalIds()->getImdbId() }}"
                       class="text-blue-800 flex items-center"
                       target="_blank">{{ $movie->getExternalIds()->getImdbId() }}
                        <x-icons.external-link class="h-6 w-6"/>
                    </a>
                    <button type="button" @click="copy('{{ $movie->getExternalIds()->getImdbId() }}')"  class="text-blue-800 flex items-center">
                        <x-icons.copy class="h-6 w-6"/>
                        Copy
                    </button>
                @endif
            </div>
            @if($editMode)
                <div wire:loading>
                    Adding...
                </div>

                <div wire:loading.remove>
                    @if($added)
                        <span class="text-blue-800">Added!</span>
                    @else
                        <button class="px-4 py-3 text-white bg-blue-800 rounded flex items-center space-x-1"
                                wire:click="addToList('{{ $movie->getId() }}')">
                            <span>Add to Notion</span>
                            <x-icons.plus class="h-6 w-6"/>
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>

</div>
