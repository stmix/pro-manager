<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Grid as ComponentsGrid;
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
            Grid::make(1)->schema([
            TextInput::make('name')
                ->label('Nazwa')
                ->required(),
            
                RichEditor::make('description')
                ->label('Opis')
                ->rules(['min:3', 'max:10000'])
                ->required()
                ->toolbarButtons([
                    'bold', 'italic', 'underline', 'strike', 'link', 'ordered-list', 'unordered-list', 'blockquote',
                    'code', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'align-left', 'align-center', 'align-right',
                    'image', 'video',
                ]),
            ]),
            DatePicker::make('start_date')
                ->label('Data rozpoczęcia')
                ->nullable(),
            DatePicker::make('end_date')
                ->label('Data zakończenia')
                ->nullable(),

            Select::make('status')
                ->label('Status')
                ->options(function () {
                    $project = $this->getOwnerRecord();
                    if ($project) {
                        $statuses = [];
                        
                        foreach($project->allowed_statuses as $st) {
                            $statuses[$st['name']] = $st['name'];
                        }

                        return $statuses;
                    }

                    return [];
                })
                ->required(),

            Select::make('assigned_user')
                ->label('Wykonywane przez')
                ->options(function () {
                    $project = $this->getOwnerRecord();
                    if ($project) {
                        $members = [];
                        foreach($project->members()->get() as $member) {
                            $members[$member['id']] = $member['name'];
                        }

                        return $members;
                    }

                    return [];
                }),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nazwa zadania')->sortable()->searchable()
                ->formatStateUsing(fn ($record) => '[' . $record->project->short_name . '-' . $record->number . '] ' . $record->name),
                Tables\Columns\TextColumn::make('assigned.name')->label('Wykonywane przez')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('status')->label('Status')->sortable()->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filtruj według statusu')
                    ->options(function () {
                        $ownerRecord = $this->getOwnerRecord();
                        return $ownerRecord
                            ? collect($ownerRecord->allowed_statuses)->pluck('name', 'name')->toArray()
                            : [];
                    })
                    ->multiple(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make()->visible(fn () => true),
            ])
            ->headerActions([
                Action::make('Dodaj zadanie')
                ->label('Dodaj zadanie')
                ->url(fn () => '/admin/tasks/create?project_id='.$this->getOwnerRecord()->id)
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
            ComponentsGrid::make(1)->schema([
                
                TextEntry::make('name')
                ->label('Nazwa')
                ->formatStateUsing(fn ($record) => '[' . $record->project->short_name . '-' . $record->number . '] ' . $record->name),

                TextEntry::make('description')
                ->label('Opis')
                ->html(),
            
            ]),

            TextEntry::make('start_date')
            ->label('Data rozpoczęcia')
            ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('d/m/Y')),

            TextEntry::make('end_date')
            ->label('Data zakończenia')
            ->formatStateUsing(fn ($state) => strtotime($state) != false ? Carbon::parse($state)->format('d/m/Y') : 'Brak daty')
            ->default('brak daty'),

            TextEntry::make('status')
            ->label('Status'),

            TextEntry::make('assigned.name')
            ->label('Wykonywane przez')
            ->default('-'),

            TextEntry::make('created_at')
            ->label('Utworzono')
            ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('d/m/Y H:i')),

            TextEntry::make('updated_at')
            ->label('Ostatnia aktualizacja')
            ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('d/m/Y H:i')),
        ]);
    }
}
