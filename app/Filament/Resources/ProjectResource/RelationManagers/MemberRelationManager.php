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
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Facades\Auth;

class MemberRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    protected static ?string $title = 'Uczestnicy';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nazwa użytkownika'),
                Tables\Columns\TextColumn::make('is_accepted')->label('Status')
                ->formatStateUsing(function ($state, $record) {
                    $ownerId = $this->getOwnerRecord()->owner;
                    if ($record->id === $ownerId) {
                        return 'Właściciel';
                    }
                    return $state ? 'Zaakceptowane' : 'Oczekujące';
                }),
            ])
            ->filters([])
            ->actions([
                Action::make('remove')
                ->label('Usuń z projektu')
                ->action(function ($record) {
                    $this->getOwnerRecord()
                        ->members()
                        ->detach($record->id);
                    
                    $this->getOwnerRecord()
                        ->tasks()
                        ->where('assigned_user', $record->id)
                        ->update(['assigned_user' => null]);
                })
                ->requiresConfirmation()
                ->authorize(fn ($record) => 
                    ($this->getOwnerRecord()->owner === Auth::user()->id || $record->id === Auth::user()->id) 
                    && $record->id != $this->getOwnerRecord()->owner
                )
                ->icon('heroicon-o-trash'),
            ])
            ->headerActions([
                Action::make('add')
                ->label('Dodaj uczestnika')
                ->url(fn () => route('projects.add-member', [ 'projectId' => $this->getOwnerRecord()->id ]))
                ->icon('heroicon-o-plus'),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
        ]);
    }
    
}
