<x-filament::page>
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold">Twoje projekty</h2>
        <x-filament::button wire:click="redirectToAddProject">Dodaj projekt</x-filament::button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div>
        @livewire('project-tile', ['title' => 'Projekt 1', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'])
        </div>
        
        <div>
            @livewire('project-tile', ['title' => 'Projekt 2', 'content' => 'Aliquam tincidunt arcu non est.'])
        </div>
        
        <div>
            @livewire('project-tile', ['title' => 'Projekt 3', 'content' => 'Aliquam tincidunt arcu non est.'])
        </div>
        
        <div>
            @livewire('project-tile', ['title' => 'Projekt 4', 'content' => 'Aliquam tincidunt arcu non est.'])
        </div>
        
        <div>
            @livewire('project-tile', ['title' => 'Projekt 5', 'content' => 'Aliquam tincidunt arcu non est.'])
        </div>
        
        <div>
            @livewire('project-tile', ['title' => 'Projekt 6', 'content' => 'Aliquam tincidunt arcu non est.'])
        </div>
    </div>
</x-filament::page>