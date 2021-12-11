<div class="flex flex-col space-y-10">
    @if(session()->get('editModeEnabled') === true)
        <div>Edit mode enabled</div>
    @endif
    <div class="flex flex-col space-y-4">
        <div>
            <input type="search" class="rounded text-2xl w-full" wire:model.debounce.500ms="searchTerm">
        </div>
        <div>
            <label class="flex items-center space-x-2">
                <span>Show movies without IMDB Id</span>
                <input type="checkbox" class="rounded text-2xl" wire:model="showMoviesWithoutImdb">
            </label>
        </div>
    </div>
    <div class="space-y-4">
        @forelse($movies as $movie)
            <div class="odd:bg-blue-100 even:bg-blue-50" wire:loading.remove>
                <livewire:movie-item :movie="$movie" :key="$movie->getId()"/>
            </div>
        @empty
            <div wire:loading.remove>No movies found...</div>
        @endforelse
        <div wire:loading>
            Loading results...
        </div>
    </div>
</div>
