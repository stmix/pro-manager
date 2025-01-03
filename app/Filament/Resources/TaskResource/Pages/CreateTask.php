<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    public function getTitle(): string
    {
        $projectId = request()->get('project_id');

        if (!$projectId) {
            return 'Tworzenie zadania';
        }

        $project = Project::find($projectId);
        if (!$project) {
            return 'Tworzenie zadania';
        }

        $highestNumber = Task::where('project_id', $projectId)->max('number') ?? 0;

        return sprintf(
            'Tworzenie zadania %s-%d',
            $project->short_name,
            $highestNumber + 1
        );
    }
}
