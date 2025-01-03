<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Models\Task;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Forms;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;

class TaskRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static ?string $title = 'Zadania';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nazwa zadania')
                ->required(),
            Forms\Components\DatePicker::make('due_date')
                ->label('Data wykonania')
                ->nullable(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nazwa zadania'),
                Tables\Columns\TextColumn::make('assigned_user')->label('Wykonywane przez'),
                Tables\Columns\TextColumn::make('status')->label('Status'),
            ])
            ->filters([])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->headerActions([
                Action::make('Dodaj zadanie')
                ->label('Dodaj zadanie')
                ->url(fn () => '/admin/tasks/create?project_id='.$this->getOwnerRecord()->id)
                ->icon('heroicon-o-plus'),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            TextEntry::make('name')
                ->label('Nazwa'),

                TextEntry::make('description')
                ->label('Opis'),

                TextEntry::make('start_date')
                ->label('Data rozpoczęcia')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('d/m/Y')),

                TextEntry::make('end_date')
                ->label('Data zakończenia')
                ->formatStateUsing(fn ($state) => $state ? Carbon::parse($state)->format('d/m/Y') : 'Brak daty'),

                TextEntry::make('status')
                ->label('Status'),

                TextEntry::make('project.number')
                ->label('Numer projektu')
                ->formatStateUsing(fn ($state) => $state ? $state : 'Brak projektu'),

                TextEntry::make('created_at')
                ->label('Data utworzenia')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('d/m/Y H:i')),

                TextEntry::make('updated_at')
                ->label('Ostatnia aktualizacja')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('d/m/Y H:i')),
        ]);
    }
}
