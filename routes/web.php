<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'pages/welcome');
Route::view('/home', 'pages/home')->name('home');
Route::view('/blog', 'pages/blog')->name('blog');

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'admin\dashboard')->name('dashboard');
    Route::view('category', 'admin\category')->name('category');
    Route::view('post', 'admin\post')->name('post');
    Route::view('profile', 'admin\profile')->name('profile');
});

require __DIR__.'/auth.php';
