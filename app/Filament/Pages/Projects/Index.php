<?php

namespace App\Filament\Pages\Projects;

use App\Livewire\ProjectTile;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Index extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.projects.index';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Projekty';
    protected static ?string $navigationGroup = 'Panel użytkownika';

    public function getHeading(): string
    {
        return 'Witaj, '.Auth::user()->name.'!';
    }

    public function redirectToAddProject()
    {
        return redirect()->route('projects.add');
    } 
}
