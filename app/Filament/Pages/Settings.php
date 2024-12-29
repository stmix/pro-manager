<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Page;
use Filament\Forms\Form;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static string $view = 'filament.pages.settings';

    protected static ?string $title = 'Ustawienia';
    protected static ?string $navigationGroup = 'Pozostałe';
    protected static ?int $navigationSort = 999;

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('input_1')
                ->label('Pole tekstowe 1')
                ->required(),  // Wymagane pole

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

    public function getHeading(): string
    {
        return 'Ustawienia';
    }


}
