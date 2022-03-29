<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//authors
    Route::get('/authors', [App\Http\Controllers\AuthorController::class, 'index'])->name('authors');
    Route::get('/authors/{id}', [App\Http\Controllers\AuthorController::class, 'view'])->name('author.view');
    Route::get('/authors/{id}/delete', [App\Http\Controllers\AuthorController::class, 'delete'])->name('author.delete');

//books
    Route::get('/books', [App\Http\Controllers\BookController::class, 'index'])->name('books');
    Route::get('/books/create', [App\Http\Controllers\BookController::class, 'create'])->name('book.create');
    Route::get('/books/{id}', [App\Http\Controllers\BookController::class, 'view'])->name('book.view');
    Route::get('/books/{id}/delete', [App\Http\Controllers\BookController::class, 'delete'])->name('book.delete');
    Route::post('/books', [App\Http\Controllers\BookController::class, 'store'])->name('book.store');
});


