<?php

use App\Http\Controllers\PageController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

//EN ROUTES

Route::group(['prefix' => "en", 'middleware' => [SetLocale::class]], function () {
    Route::get('/', [PageController::class, 'homepage'])->name('en.homepage');
    Route::get('/blog', [PageController::class, 'postsArchive'])->name('en.blog');
    Route::get('/blog/{post}', [PageController::class, 'post'])->name('en.post');
    Route::get('/events', [PageController::class, 'eventsArchive'])->name('en.events');
    Route::get('/events/{event}', [PageController::class, 'event'])->name('en.event');
});

//SK ROUTES

Route::get('/', [PageController::class, 'homepage'])->name('homepage');
Route::get('/blog', [PageController::class, 'postsArchive'])->name('blog');
Route::get('/blog/{post}', [PageController::class, 'post'])->name('post');
Route::get('/eventy', [PageController::class, 'eventsArchive'])->name('events');
Route::get('/eventy/{event}', [PageController::class, 'event'])->name('event');
