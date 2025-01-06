<x-filament-panels::page>
    
    @if (count($relationManagers = $this->getRelationManagers()))
        <x-filament-panels::resources.relation-managers
            :active-manager="$activeRelationManager ?? 'tasks'"
            :managers="$relationManagers"
            :owner-record="$record"
            :page-class="static::class"
        />
    @endif

    @if ($this->hasInfolist())
        {{ $this->infolist }}
    @else
    <div class="mt-6">
    <h1 class="text-2xl font-medium">Szczegóły projektu</h1>
    </div>
        {{ $this->form }}
    @endif
</x-filament-panels::page>