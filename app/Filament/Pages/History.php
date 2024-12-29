<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class History extends Page implements HasTable
{
    use InteractsWithTable;


    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static string $view = 'filament.pages.history';

    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'Historia';
    protected static ?string $navigationGroup = 'Panel użytkownika';

    public function table(Table $table): Table
    {
        return $table
        ->query(User::query())  // Używamy query z modelu HistoryItem
        ->columns([
            TextColumn::make('title')->label('Tytuł'),
            TextColumn::make('slug')->label('Slug'),
            IconColumn::make('is_featured')
                ->boolean()
                ->label('Czy wyróżniony'),
        ]);
    }
}
