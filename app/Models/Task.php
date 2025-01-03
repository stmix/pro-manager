<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'number',
        'status',
        'start_date',
        'end_date',
        'assigned_user',
        'project_id'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}