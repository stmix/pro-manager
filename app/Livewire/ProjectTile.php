<?php

namespace App\Livewire;

use Filament\Widgets\Widget;

class ProjectTile extends Widget
{
    public $title;
    public $content;

    public function mount($title, $content)
    {
        $this->title = $title;
        $this->content = $content;
    }
    
    protected static string $view = 'livewire.project-tile';

    protected int | string | array $columnSpan = 'full';
}
