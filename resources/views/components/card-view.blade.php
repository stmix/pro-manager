<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @if ($projects)
        @foreach ($projects as $record)
            <div class="p-4 border rounded-lg shadow bg-white">
                <h3 class="text-lg font-bold">{{ $record->name }}</h3>
                <p>{{ $record->description }}</p>
                <p class="text-sm text-gray-600">Start: {{ $record->start_date }}</p>
                <p class="text-sm text-gray-600">End: {{ $record->end_date }}</p>
            </div>
        @endforeach
    @else
        <p class="text-gray-600">Brak danych do wyświetlenia.</p>
    @endif
</div>
