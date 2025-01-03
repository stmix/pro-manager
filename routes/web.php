<?php

use App\Filament\Pages\AddMember;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/projects/members/invite/{projectId}', AddMember::class)->name('projects.add-member');
});

