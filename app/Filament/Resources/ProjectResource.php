<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Filament\Tables\CardView;
use App\Models\Project;
use Carbon\Carbon;
use Closure;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
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

    protected static ?string $navigationLabel = 'Projekty';

    public function sendShortNameNotification()
    {
        Notification::make()
            ->title('Nazwa skrócona jest już zajęta')
            ->danger()  // Typ powiadomienia - niebezpieczne (błąd)
            ->send();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([

            TextInput::make('name')
            ->afterStateUpdated(function (callable $set, $state) {
                $set('short_name', strtoupper(substr($state, 0, 3)));
            })
            ->live(debounce: 1)
                ->label('Nazwa')
                ->rules(['min:3', 'max:255'])
                ->required(),

            TextInput::make('short_name')
            ->label('Nazwa skrócona')
            ->rules([
                'max:10',
                function ($get) { 
                    if (Project::where('short_name', $get('short_name'))->where('id', '!=', $get('id'))
                    ->exists())
                {
                    Notification::make()
                    ->title('Nazwa skrócona jest już zajęta!')
                    ->danger()
                    ->send();
                    return;
                } else
                {
                    return null;
                }
            }
            ])
            ->required(),

            Grid::make(1)->schema([
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

            Hidden::make('owner')
            ->default(Auth::user()->id),

            Grid::make(1)->schema([
                Repeater::make('allowed_statuses')
                    ->label('Dozwolone statusy')
                    ->schema([
                        TextInput::make('name')
                            ->hiddenLabel()
                            ->required(),
                    ])
                    ->addActionLabel('Dodaj status')
                    ->default([]),
            ]),

            DatePicker::make('start_date')
                ->displayFormat('d/m/Y')
                ->label('Data rozpoczęcia')
                ->required()
                ->default(Carbon::now())
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
                ->reactive()
                ->minDate(fn (callable $get) => 
                    $get('start_date') 
                        ? Carbon::parse($get('start_date'))->startOfDay()->toDateString() 
                        : null
                )
                ->placeholder('Data planowanego zakończenia projektu')
                ->nullable()
                ->native(false),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->query(Project::whereHas('members', function ($query) {
            $query->where('user_id', Auth::id())->where('is_accepted', true);
        }))
        ->columns([
            TextColumn::make('name')->label('Nazwa projektu')->sortable()->searchable(),
            TextColumn::make('short_name')->label('Nazwa skrócona')->sortable()->searchable(),
            TextColumn::make('created_at')
                ->label('Utworzony')
                ->sortable()
                ->dateTime('d/m/Y H:i'),
            TextColumn::make('updated_at')
                ->label('Ostatnia edycja')
                ->sortable()
                ->dateTime('d/m/Y H:i'),
        ])
        ->filters([
            Tables\Filters\Filter::make('recent')
                ->label('Dodane w ostatnich 7 dniach')
                ->query(fn (Builder $query) => $query->where('created_at', '>=', now()->subDays(7))),
            Tables\Filters\Filter::make('active')
                ->label('Aktywne projekty')
                ->query(fn (Builder $query) => $query->whereNull('end_date')->orWhere('end_date', '>=', now())),
            Tables\Filters\Filter::make('completed')
                ->label('Zakończone projekty')
                ->query(fn (Builder $query) => $query->whereNotNull('end_date')->where('end_date', '<', now())),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ])
        ->defaultSort('created_at', 'desc');
    }

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
