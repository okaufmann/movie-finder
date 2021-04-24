<div>
    <div>
    <input type="search" class="rounded text-2xl w-full" wire:model.debounce.500ms="searchTerm">
    </div>
    <div>
        @forelse($movies as $movie)
            <livewire:movie-item :movie="$movie" :key="$movie->getId()"/>
        @empty
            <div>No movies found...</div>
        @endforelse
    </div>
</div>
