<?php

namespace App\Filament\Pages\Projects;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Page;

class AddProject extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.projects.add-project';

    protected static bool $shouldRegisterNavigation = false;

    public function form(Form $form): Form
    {
        return $form->schema([

            Toggle::make('is_admin'),

            TextInput::make('input_1')
                ->label('Pole tekstowe 1')
                ->required(),

            TextInput::make('input_2')
                ->label('Pole tekstowe 2')
                ->required(),

            Select::make('select_1')
            ->options(['jeden', 'dwa', 'trzy']),

            Checkbox::make('checkbox')
            ->label('Wymagane uprawnienia'),

            CheckboxList::make('technologies')
            ->options([
                'tailwind' => 'Tailwind CSS',
                'alpine' => 'Alpine.js',
                'laravel' => 'Laravel',
                'livewire' => 'Laravel Livewire',
            ]),

            Toggle::make('is_admin'),

        ]);
    }
}
