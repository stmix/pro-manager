<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Filament\Tables\CardView;
use App\Models\Project;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([

            TextInput::make('name')
            ->afterStateUpdated(function (callable $set, $state) {
                $set('short_name', strtoupper(substr($state, 0, 3)));
            })
            ->live(debounce: 50)
                ->label('Nazwa')
                ->rules(['min:3', 'max:255'])
                ->required(),

            TextInput::make('description')
                ->label('Opis')
                ->rules(['min:3', 'max:10000'])
                ->required(),

            TextInput::make('short_name')
                ->label('Nazwa skrócona')
                ->rules(['max:10'])
                ->required(),

            Hidden::make('owner')
            ->default(Auth::user()->id),

            Repeater::make('allowed_statuses')
                ->label('Dozwolone statusy')
                ->schema([
                    TextInput::make('name')
                        ->label('Nazwa statusu')
                        ->required(),
                ])
                ->addActionLabel('Dodaj status')
                ->default([]),
                DatePicker::make('start_date')
                    ->displayFormat('d/m/Y')
                    ->label('Data rozpoczęcia')
                    ->required()
                    ->default(Carbon::now())
                    ->native(false),

                DatePicker::make('end_date')
                    ->displayFormat('d/m/Y')
                    ->label('Data zakończenia')
                    ->placeholder('Data planowanego zakończenia projektu')
                    ->nullable()
                    ->native(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('name')->label('Nazwa projektu'),
            TextColumn::make('short_name')->label('Nazwa skrócona'),
            TextColumn::make('created_at')
                ->label('Utworzony'),
            TextColumn::make('updated_at')
                ->label('Ostatnia edycja'),
        ]);
    }

    // public static function table(Table $table): Table
    // {
    //     return $table
    //         ->columns([])
    //         ->filters([])
    //         ->actions([])
    //         ->bulkActions([])
    //         ->view('components.card-view', [
    //             'projects' => Project::all(),
    //         ]);
    // }



    public static function getRelations(): array
    {
        return [
            'tasks' => RelationManagers\TaskRelationManager::class,
            'members' => RelationManagers\MemberRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
