<?php

namespace App\Filament\Pages;

use App\Models\Project;
use App\Models\ProjectsUsers;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class AddMember extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.add-member';

    protected static bool $shouldRegisterNavigation = false;

    public $email;
    public $projectId;

    public function form(Form $form): Form
    {
        return $form->schema([

            TextInput::make('email')
                ->label('Adres e-mail')
                ->rules(['email', 'required', 'min:3', 'max:255'])
                ->required(),

            Hidden::make('projectId')
            ->default(request()->projectId),
        ]);
    }

    public function getTitle(): string
    {
        $projectId = request()->projectId;
        if ($projectId) {
            $project = Project::find($projectId);
            if ($project) {
                return 'Dodaj użytkownika do projektu: ' . $project->name;
            }
        }
        return 'Dodaj użytkownika do projektu';
    }

    public function getActions(): array
    {
        return [
            Action::make('sendInvite')
                ->label('Wyślij zaproszenie')
                ->action('sendInvite')
                ->color('primary')
                ->icon('heroicon-o-paper-airplane'),
        ];
    }

    public function sendInvite(): void
    {
        $projectId = $this->form->getState()['projectId'];

        if (!$projectId) {

            Notification::make()
            ->title('Nie podano identyfikatora projektu!')
            ->danger()
            ->send();
            
            return;
        }

        $project = Project::find($projectId);

        if (!$project) {

            Notification::make()
            ->title('Nie znaleziono projektu!')
            ->danger()
            ->send();
            
            return;
        }

        $email = $this->form->getState()['email'];

        $user = \App\Models\User::where('email', $email)->first();

        if (!$user) {
            Notification::make()
            ->title('Użytkownik o podanym adresie e-mail nie istnieje!')
            ->danger()
            ->send();
            return;
        }

        $pr_user = ProjectsUsers::where('project_id', $projectId)->where('user_id', $user->id)->first();

        if ($pr_user) {
            Notification::make()
            ->title('Użytkownik o podanym adresie e-mail nie może zostać zaproszony!')
            ->danger()
            ->send();
            return;
        }

        $projectUser = new ProjectsUsers([
            'project_id' => $projectId,
            'user_id' => $user->id,
            'is_accepted' => false,
        ]);

        $projectUser->save();

        Notification::make()
            ->title('Zaproszenie do projektu zostało wysłane pomyślnie!')
            ->success()
            ->send();
    }
}
