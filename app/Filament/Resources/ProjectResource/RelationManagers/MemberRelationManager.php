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

class MemberRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nazwa zadania'),
                Tables\Columns\TextColumn::make('is_accepted')->label('Status'),
            ])
            ->filters([])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->headerActions([
                // Możesz dodać akcje w nagłówku, np. przycisk "Dodaj zadanie"
                Action::make('add')
                ->label('Dodaj uczestnika')
                ->url(fn () => route('projects.add-member', [ 'projectId' => $this->getOwnerRecord()->id ]))
                ->icon('heroicon-o-plus'),// link do strony tworzenia zadania
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
        ]);
    }
    
}
