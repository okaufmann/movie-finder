<div class="flex flex-col space-y-10">
    <div>
        <input type="search" class="rounded text-2xl w-full" wire:model.debounce.500ms="searchTerm">
    </div>
    <div>
        @forelse($movies as $movie)
            <div class="odd:bg-gray-200 even:bg-gray-100" wire:loading.remove>
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
