<?php

namespace App\Filament\Pages;

use App\Models\ProjectsUsers;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class Invitations extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static string $view = 'filament.pages.invitations';

    protected static ?string $title = 'Zaproszenia';

    public static function getNavigationBadge(): ?string
    {
        return ProjectsUsers::where('user_id', Auth::id())
            ->where('is_accepted', false)
            ->count();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ProjectsUsers::where('user_id', Auth::id())
                    ->where('is_accepted', false)
            )
            ->columns([
                TextColumn::make('project.name')
                    ->label('Nazwa projektu')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('is_accepted')
                    ->label('Status')
                    ->formatStateUsing(function ($state) {
                        return $state ? 'Zaakceptowane' : 'Oczekujące';
                    })
            ])
            ->actions([
                Action::make('accept')
                    ->label('Akceptuj')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->action(function (ProjectsUsers $record) {
                        $record->update(['is_accepted' => true]);
                        Notification::make()
                        ->title('Zaproszenie zaakceptowane.')
                        ->success()
                        ->send();
                    }),

                Action::make('reject')
                    ->label('Odrzuć')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->action(function (ProjectsUsers $record) {
                        $record->delete();

                        Notification::make()
                        ->title('Zaproszenie odrzucone.')
                        ->success()
                        ->send();
                    }),
            ])
            ->headerActions([]);
    }
}
