<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectsUsers extends Model
{

    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'project_id',
        'is_accepted'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
