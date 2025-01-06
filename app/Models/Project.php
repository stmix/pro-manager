<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'short_name',
        'owner',
        'start_date',
        'end_date',
        'allowed_statuses',
    ];

    protected static function booted()
    {
        static::created(function (Project $project) {
            \App\Models\ProjectsUsers::create([
                'user_id' => Auth::user()->id,
                'project_id' => $project->id,
                'is_accepted' => true,
            ]);
        });
    }

    protected $casts = [
        'allowed_statuses' => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'projects_users', 'project_id', 'user_id');
    }
}
