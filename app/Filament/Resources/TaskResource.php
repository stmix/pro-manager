<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form->schema([


            Grid::make(1)->schema([
                TextInput::make('name')
                    ->label('Nazwa')
                    ->rules(['min:3', 'max:255'])
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
                ->displayFormat('d/m/Y')
                ->label('Data rozpoczęcia')
                ->default(Carbon::now())
                ->required()
                ->native(false)
                ->reactive()
                ->afterStateUpdated(function (callable $get, callable $set, $state) {
                    $currentDate = Carbon::now();
                    
                    $newEndDate = $state && Carbon::parse($state)->gt($currentDate)
                        ? Carbon::parse($state)
                        : $currentDate;
            
                    $set('end_date', $newEndDate->format('Y-m-d'));
                }),

            DatePicker::make('end_date')
                ->displayFormat('d/m/Y')
                ->label('Data zakończenia')
                ->placeholder('Data planowanego zakończenia zadania')
                ->reactive()
                ->minDate(fn (callable $get) => 
                    $get('start_date') 
                        ? Carbon::parse($get('start_date'))->startOfDay()->toDateString() 
                        : null
                )
                ->nullable()
                ->native(false),

                Hidden::make('number')
                ->default(function () {
                    $projectId = request()->get('project_id');

                    if ($projectId) {
                        $project = Project::find($projectId);

                        if ($project) {
                            $maxTaskNumber = $project->tasks()->max('number');

                            return $maxTaskNumber ? $maxTaskNumber + 1 : 1;
                        }
                    }
                    return 1;
                }),


                Hidden::make('project_id')
                ->default(function () {
                    $projectId = request()->get('project_id');
                    if ($projectId) {
                            return $projectId;
                    }
    
                    return null;
                }),

                Hidden::make('author')
                ->default(function () {
                    $user = Auth::user()->id;
                    return $user;
                }),

                Select::make('status')
                ->label('Status')
                ->options(function () {
                    $projectId = request()->project_id;

                    if ($projectId) {
                        $project = Project::find($projectId);
                        $statuses = [];
                        
                        foreach($project->allowed_statuses as $st) {
                            $statuses[$st['name']] = $st['name'];
                        }

                        return $statuses;
                    }

                    return [];
                }),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            TextEntry::make('name')
                ->label('Nazwa'),

                TextEntry::make('description')
                ->label('Opis')
                ->html(),

                TextEntry::make('start_date')
                ->label('Data rozpoczęcia')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('d/m/Y')),

                TextEntry::make('end_date')
                ->label('Data zakończenia')
                ->formatStateUsing(fn ($state) => $state ? Carbon::parse($state)->format('d/m/Y') : 'Brak daty'),

                TextEntry::make('status')
                ->label('Status'),

                TextEntry::make('number')
                ->label('Numer zadania')
                ->formatStateUsing(fn ($state) => $state ? $state : 'Brak zadania'),

                TextEntry::make('created_at')
                ->label('Data utworzenia')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('d/m/Y H:i')),

                TextEntry::make('updated_at')
                ->label('Ostatnia aktualizacja')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('d/m/Y H:i')),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'view' => Pages\ViewTask::route('/{record}'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
