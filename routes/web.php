<?php

use App\Filament\Pages\Projects\AddProject;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/add-project', AddProject::class)->name('projects.add');
});

